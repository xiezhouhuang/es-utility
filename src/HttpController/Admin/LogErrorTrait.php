<?php

namespace WonderGame\EsUtility\HttpController\Admin;

trait LogErrorTrait
{
    protected function _search()
    {
        $filter = $this->filter();

        $where = ['time' => [[$filter['begintime'], $filter['endtime']], 'between']];
        if (!empty($this->get['type']))
        {
            $where['type'] = $this->get['type'];
        }
        return $where;
    }

    public function multiple()
    {
        // 客户端是批量发送，成功后清空report, 为啥不调saveAll，避免因为单条失败，导致该用户error report永远失败
        foreach ($this->post as $error)
        {
            try {

                if (empty($error['time'])) {
                    $error['time'] = time();
                }
                $error['stack'] = $error['stack'] ?? '';

                // 创建克隆对象，重要
                $model = $this->Model->_clone();
                $model->data($error)->save();
            }
            catch (\Exception | \Throwable $e) {}
        }
        $this->success();
    }
}