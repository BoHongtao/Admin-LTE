<?php

namespace app\components;

/**
 * Created by PhpStorm.
 * User: gaofeng
 * Date: 14-8-9
 * Time: 上午3:44
 *
 * System V message queue IPC通信消息队列封装
 * 如果你想修改一个队列最大能够存储的字节数，请确认你的脚本具有root权限
 */
class SystemMessageQueue {

    //消息分组类型，用于将一个消息队列中的信息进行分组
    private $msg_type;
    //队列标志
    private $queue;
    //是否序列化
    private $serialize_needed;
    //无法写入队列时，是否阻塞
    private $block_send;
    //设置位MSG_IPC_NOWAIT，如果无法获取到一个消息，则不等待；如果设置位NULL，则会等待消息到来
    private $option_receive;
    //希望接收到的最大消息大小
    private $maxsize;
    //IPC通信KEY
    private $key_t;
    //Project identifier
    private $proj_id;

    /**
     * @param $ipc_filename IPC通信标志文件，用于获取唯一IPC KEY
     * @param $msg_type 消息类型
     * @param bool $serialize_needed 是否序列化
     * @param bool $block_send 无法写入队列时，是否阻塞
     * @param int $option_receive 设置位MSG_IPC_NOWAIT，如果无法获取到一个消息，则不等待；如果设置位NULL，则会等待消息到来
     * @param int $maxsize 希望接收到的最大消息
     */
    public function __construct($msg_type, $ipc_filename = __FILE__,$proj_id='A', $serialize_needed = false, $block_send = false, $option_receive = MSG_IPC_NOWAIT, $maxsize = 100000) {
        $this->msg_type = $msg_type;
        $this->serialize_needed = $serialize_needed;
        $this->block_send = $block_send;
        $this->option_receive = $option_receive;
        $this->maxsize = $maxsize;
        $this->proj_id = $proj_id;
        $this->init_queue($ipc_filename);
    }

    /**
     * 初始化一个队列
     * @param $ipc_filename
     * @param $msg_type
     * @throws Exception
     */
    public function init_queue($ipc_filename) {
        $this->key_t = $this->get_ipc_key($ipc_filename);
        $this->queue = msg_get_queue($this->key_t);
        if (!$this->queue)
            throw new \Exception('msg_get_queue failed');
    }

    /**
     * @param $ipc_filename
     * @param $msg_type
     * @return int
     * @throws Exception
     */
    public function get_ipc_key($ipc_filename) {
        $key_t = ftok($ipc_filename,$this->proj_id);
        if ($key_t == -1)
            throw new \Exception('ftok error');

        return $key_t;
    }

    /**
     * 从队列获取一个
     * @return bool
     * @throws Exception
     */
    public function get() {
        $queue_status = $this->status();
        if ($queue_status['msg_qnum'] > 0) {
            if (msg_receive($this->queue, $this->msg_type, $msgtype_erhalten, $this->maxsize, $data, $this->serialize_needed, $this->option_receive, $err) === true) {
                return $data;
            } else {
                throw new \Exception($err);
            }
        } else {
            return false;
        }
    }

    /**
     * 写入队列
     * @param $message
     * @throws Exception
     */
    public function put($message) {
        if (!msg_send($this->queue, $this->msg_type, $message, $this->serialize_needed, $this->block_send, $err) === true) {
            throw new \Exception($err);
        }

        return true;
    }

    /*
     * 返回值数组下标如下：
     * msg_perm.uid	 The uid of the owner of the queue. 用户ID
     * msg_perm.gid	 The gid of the owner of the queue. 用户组ID
     * msg_perm.mode	 The file access mode of the queue. 访问模式
     * msg_stime	 The time that the last message was sent to the queue. 最后一次队列写入时间
     * msg_rtime	 The time that the last message was received from the queue.  最后一次队列接收时间
     * msg_ctime	 The time that the queue was last changed. 最后一次修改时间
     * msg_qnum	 The number of messages waiting to be read from the queue. 当前等待被读取的队列数量
     * msg_qbytes	 The maximum number of bytes allowed in one message queue.  一个消息队列中允许接收的最大消息总大小
     *               On Linux, this value may be read and modified via /proc/sys/kernel/msgmnb.
     * msg_lspid	 The pid of the process that sent the last message to the queue. 最后发送消息的进程ID
     * msg_lrpid	 The pid of the process that received the last message from the queue. 最后接收消息的进程ID
     *
     * @return array
     */

    public function status() {
        $queue_status = msg_stat_queue($this->queue);
        return $queue_status;
    }

    /**
     * 获取队列当前堆积状态
     * @return mixed
     */
    public function size() {
        $status = $this->status();
        return $status['msg_qnum'];
    }

    /**
     * allows you to change the values of the msg_perm.uid,
     * msg_perm.gid, msg_perm.mode and msg_qbytes fields of the underlying message queue data structure
     * 可以用来修改队列运行接收的最大读取的数据
     *
     * @param $key 状态下标
     * @param $value 状态值
     * @return bool
     */
    public function set_status($key, $value) {
        $this->check_set_privilege($key);

        if ($key == 'msg_qbytes')
            return $this->set_max_queue_size($value);

        $queue_status[$key] = $value;
        return msg_set_queue($this->queue, $queue_status);
    }

    /**
     * 删除一个队列
     * @return bool
     */
    public function queue_remove() {
        return msg_remove_queue($this->queue);
    }

    //修改队列能容纳的最大字节数，需要root权限
    /**
     * @param $size
     * @return bool
     * @throws Exception
     */
    public function set_max_queue_size($size) {
        $user = get_current_user();
        if ($user !== 'root')
            throw new \Exception('changing msg_qbytes needs root privileges');

        return $this->set_status('msg_qbytes', $size);
    }

    /**
     * 判断一个队列是否存在
     * @param $key
     * @return bool
     */
    public function queue_exists($key) {
        return msg_queue_exists($key);
    }

    /**
     * 检查修改队列状态的权限
     * @param $key
     * @throws Exception
     */
    private function check_set_privilege($key) {
        $privilege_field = array('msg_perm.uid', 'msg_perm.gid', 'msg_perm.mode');
        if (!in_array($key, $privilege_field)) {
            throw new \Exception('you can only change msg_perm.uid, msg_perm.gid, msg_perm.mode and msg_qbytes. And msg_qbytes needs root privileges');
        }
    }

}
