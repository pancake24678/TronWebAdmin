<?php

namespace addons\encryptpwd;

use addons\encryptpwd\library\FieldHandle;
use app\common\library\Menu;
use think\Addons;
use think\Request;

/**
 * 插件
 */
class Encryptpwd extends Addons
{

    // 存储每个路径被调用的次数
    protected $callIndexMap = [];

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        set_addon_config('encryptpwd', ['key' => \fast\Random::alnum(16)]);
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {

        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {

        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {

        return true;
    }

    /**
     * @param $params
     */
    public function configInit(&$params)
    {
        $config = $this->getConfig();
        $excludedurls = array_filter(explode("\n", str_replace(["\r\n", "\r"], "\n", str_replace("\\", "/", $config['excludedurls']))));
        $params['encryptpwd'] = [
            'state'        => $config['state'] ?? 0,
            'selector'     => $config['selector'] ?? 'input[type=password]',
            'key'          => $config['key'] ?? '',
            'excluded'     => $this->checkUrlExcluded(),
        ];
    }

    public function actionBegin()
    {
        $config = $this->getConfig();
        if ($config['state'] && $config['key'] && !$this->checkUrlExcluded()) {
            $request = request();
            $encryptFields = base64_decode($request->param('encryptpwdFields', '', 'trim'));
            $encryptFieldsArr = array_filter(explode(',', $encryptFields));
            $encryptIv = base64_decode($request->param('encryptpwdIv', '', 'trim'));
            $encryptIvArr = array_filter(explode(',', $encryptIv));

            $postData = request()->post('', null, 'trim');
            $this->processEncryptedParams($encryptFieldsArr, $postData, $encryptIvArr);
            $request->post($postData);
        }

    }

    protected function processEncryptedParams($keys, &$postData, $ivData)
    {
        $config = $this->getConfig();
        foreach ($keys as $index => $key) {
            // 处理嵌套数组参数，如 row[a], row[b][], db[0] 等
            if (strpos($key, '[') !== false) {
                // 解析复杂键名，如 row[a] => ['row', 'a']
                preg_match('/^([^\[]+)\[(.*)$/', $key, $matches);
                if (count($matches) >= 3) {
                    $mainKey = $matches[1]; // 如 'row'
                    $subKey = $matches[2];  // 如 'a]'

                    // 移除末尾的 ']'
                    $subKey = rtrim($subKey, ']');

                    // 处理多级嵌套，如 a][b][c][d
                    $nestedKeys = explode('][', $subKey);

                    // 检查主键是否存在
                    if (isset($postData[$mainKey])) {
                        // 递归查找并处理嵌套值
                        $reference = &$postData[$mainKey];
                        $found = true;

                        foreach ($nestedKeys as $nk) {
                            // 处理空键名，表示数组元素，如 row[b][]
                            if ($nk === '') {
                                // 无法确定具体索引，需要遍历处理所有元素
                                if (is_array($reference)) {
                                    foreach ($reference as &$item) {
                                        // 对每个元素应用解密
                                        $item = $this->decryptValue($item, $config['key'], $ivData[$index]);
                                    }
                                }
                                $found = false; // 标记为已处理，不再继续嵌套查找
                                break;
                            }

                            if (isset($reference[$nk])) {
                                $reference = &$reference[$nk];
                            } else {
                                $found = false;
                                break;
                            }
                        }

                        // 如果找到了完整路径的值，进行解密
                        if ($found) {
                            $reference = $this->decryptValue($reference, $config['key'], $ivData[$index]);
                        }
                    }
                }
            } else {
                // 处理简单键名
                if (isset($postData[$key])) {
                    $postData[$key] = $this->decryptValue($postData[$key], $config['key'], $ivData[$index]);
                }
            }
        }
    }

    protected function decryptValue($data, $key, $iv)
    {
        return openssl_decrypt($data, 'AES-128-CBC', $key, 0, $iv);
    }

    protected function checkUrlExcluded()
    {
        $config = $this->getConfig();
        $url = request()->url(true);
        $excludedurls = array_filter(explode("\n", str_replace(["\r\n", "\r"], "\n", str_replace("\\", "/", $config['excludedurls']))));
        if ($excludedurls) {
            foreach ($excludedurls as $index => $item) {
                if (\addons\encryptpwd\library\splat\Glob::match(trim($item), $url)) {
                    return true;
                }
            }
        }
        return false;
    }

}
