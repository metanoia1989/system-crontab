<?php


namespace utils;


class LayUI
{
    public static function tableData($data = [], $count = 0, $code = 0, $msg = '')
    {
        return compact('data', 'count', 'code', 'msg');
    }

    /**
     * 创建检索表单
     * @param array $conditions
     * @return string
     */
    public static function makeSearchCondition(array $conditions = [])
    {
        $html = '<fieldset class="table-search-fieldset" style="display: none" id="table-search">';
        $html .= '<legend>搜索信息</legend>';
        $html .= '<div style="margin: 10px 10px 10px 10px">';
        $html .= '<form class="layui-form layui-form-pane" action="">';
        $html .= '<div class="layui-form-item">';

        foreach ($conditions as $condition) {
            switch ($condition['html']['element']) {
                case 'input':
                    $html .= static::makeInputCondition($condition);
                    break;
                case 'select':
                    $html .= static::makeSelectCondition($condition);
                    break;
            }
        }

        $html .= '<div class="layui-inline">';
        $html .= '<button type="submit" class="layui-btn layui-btn-sm layui-btn-normal" lay-submit lay-filter="data-search-btn">搜 索</button>';
        $html .= '<button type="reset" class="layui-btn layui-btn-sm layui-btn-primary">重置</button>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</form>';
        $html .= '</div>';
        $html .= '</fieldset>';

        return $html;
    }

    /**
     * 创建input检索元素
     * @param array $config
     * @return string
     */
    protected static function makeInputCondition(array $config)
    {
        $html = '<div class="layui-inline">';
        $html .= '<label class="layui-form-label">' . $config['label']['text'] . '</label>';
        $html .= '<div class="layui-input-inline">';

        if (in_array($config['html']['type'], ['radio', 'checkbox'])) {
            // radio、checkbox
            foreach ($config['html']['option'] as $option) {
                $html .= '<input type="' . $config['html']['type'] . '" name="' . $config['html']['name'] . '" value="' . $option['value'] . '" title="' . $option['key'] . '">';
            }
        } else {
            // text、number
            $html .= '<input type="' . $config['html']['type'] . '" name="' . $config['html']['name'] . '" autocomplete="off" class="layui-input" placeholder="' . $config['html']['placeholder'] . '">';
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * 创建select检索元素
     * @param array $config
     * @return string
     */
    protected static function makeSelectCondition(array $config)
    {
        $html = '<div class="layui-inline">';
        $html .= '<label class="layui-form-label">' . $config['label']['text'] . '</label>';
        $html .= '<div class="layui-input-inline">';
        $html .= '<select name="' . $config['html']['name'] . '">';
        $html .= '<option value=""></option>';

        foreach ($config['html']['option'] as $option) {
            $html .= '<option value="' . $option['value'] . '">' . $option['key'] . '</option>';
        }

        $html .= '</select>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * 创建表单
     * @param array $configs
     * @return string
     */
    public static function makeForm(array $configs)
    {
        $html = '<form class="layui-form" action="">';
        foreach ($configs as $config) {
            switch ($config['html']['element']) {
                case 'input':
                    $html .= static::makeInputForm($config);
                    break;
                case 'select':
                    $html .= static::makeSelectForm($config);
                    break;
                case 'textarea':
                    $html .= static::makeTextareaForm($config);
                    break;
            }
        }
        $html .= '<div class="layui-form-item">';
        $html .= '<div class="layui-input-block">';
        $html .= '<button class="layui-btn layui-btn-normal" lay-submit lay-filter="saveBtn">确认保存</button>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</form>';


        return $html;
    }

    /**
     * 创建表单input元素
     * @param array $config
     * @return string
     */
    protected static function makeInputForm(array $config)
    {
        if ($config['html']['type'] === 'hidden') {
            $html = '<input type="hidden" name="' . $config['html']['name'] . '" value="' . $config['html']['value'] . '">';
        } else {
            $html = '<div class="layui-form-item">';
            $html .= '<label class="layui-form-label ' . $config['label']['verify'] . '">' . $config['label']['text'] . '</label>';
            $html .= '<div class="layui-input-block">';

            if (in_array($config['html']['type'], ['radio', 'checkbox'])) {
                // radio、checkbox
                foreach ($config['html']['option'] as $option) {
                    $html .= '<input type="' . $config['html']['type'] . '" name="' . $config['html']['name'] . '" value="' . $option['value'] . '" title="' . $option['key'] . '"' . ($option['checked'] ? 'checked' : '') . '>';
                }
            } else {
                // text、number、password
                $html .= '<input type="' . $config['html']['type'] . '" name="' . $config['html']['name'] . '" lay-verify="' . $config['html']['verify'] . '" lay-reqtext="' . $config['html']['reqtext'] . '" placeholder="' . $config['html']['placeholder'] . '" value="' . $config['html']['value'] . '" class="layui-input">';
            }

            $html .= '';
            $html .= '</div>';
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * 创建表单select元素
     * @param array $config
     * @return string
     */
    protected static function makeSelectForm(array $config)
    {
        $html = '<div class="layui-form-item">';
        $html .= '<label class="layui-form-label ' . $config['label']['verify'] . '">' . $config['label']['text'] . '</label>';
        $html .= '<div class="layui-input-block">';
        $html .= '<select name="' . $config['html']['name'] . '" lay-verify="' . $config['html']['verify'] . '" lay-search>';
        $html .= '<option value=""></option>';

        foreach ($config['html']['option'] as $option) {
            $html .= '<option value="' . $option['value'] . '" ' . ($option['selected'] ? 'selected' : '') . '>' . $option['key'] . '</option>';
        }

        $html .= '</select>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * 创建表单select元素
     * @param array $config
     * @return string
     */
    protected static function makeTextareaForm(array $config)
    {
        $html = '<div class="layui-form-item layui-form-text">';
        $html .= '<label class="layui-form-label ' . $config['label']['verify'] . '">' . $config['label']['text'] . '</label>';
        $html .= '<div class="layui-input-block">';
        $html .= '<textarea name="' . $config['html']['name'] . '" placeholder="' . $config['html']['placeholder'] . '" class="layui-textarea" lay-verify="' . $config['html']['verify'] . '" lay-reqtext="' . $config['html']['reqtext'] . '">' . $config['html']['value'] . '</textarea>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
