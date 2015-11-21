<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 中文分词
 *
 * 来源于PHPCMS V9,只是进行了部分代码整理, 新增词语直接打开字典dict.csv直接在末尾添加即可。
 *
 * 示例代码：
 * $this->load->library('words/segment');
 * $this->segment->get_keyword($this->segment->split_result('中文分词'));
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-03-24
 * @updated     2014-03-24
 * @version     1.0
 */

class Segment
{
    private $rank_dic = array();
    private $one_name_dic = array();
    private $two_name_dic = array();
    private $new_word = array();
    private $source_string = '';
    private $result_string = '';
    private $split_char = ' '; //分隔符
    private $SplitLen = 4; //保留词长度
    private $especial_char = '和|的|是';
    private $new_word_limit = '在|的|与|或|就|你|我|他|她|有|了|是|其|能|对|地';
    private $common_unit = '年|月|日|时|分|秒|点|元|百|千|万|亿|位|辆';
    private $cn_number = '０|１|２|３|４|５|６|７|８|９|＋|－|％|．|ａ|ｂ|ｃ|ｄ|ｅ|ｆ|ｇ|ｈ|ｉ|ｊ|ｋ|ｌ|ｍ|ｎ|ｏ|ｐ|ｑ|ｒ|ｓ |ｔ|ｕ|ｖ|ｗ|ｘ|ｙ|ｚ|Ａ|Ｃ|Ｄ|Ｅ|Ｆ|Ｇ|Ｈ|Ｉ|Ｊ|Ｋ|Ｌ|Ｍ|Ｎ|Ｏ|Ｐ|Ｑ|Ｒ|Ｓ|Ｔ|Ｕ|Ｖ|Ｗ|Ｘ|Ｙ|Ｚ';
    private $cn_sg_num = '一|二|三|四|五|六|七|八|九|十|百|千|万|亿|数';
    private $max_len = 13; //词典最大 7 中文字，这里的数值为字节数组的最大索引
    private $min_len = 3; //最小 2 中文字，这里的数值为字节数组的最大索引
    private $cn_two_name = '端木|南宫|谯笪|轩辕|令狐|钟离|闾丘|长孙|鲜于|宇文|司徒|司空|上官|欧阳|公孙|西门|东门|左丘|东郭|呼延|慕容|司马|夏侯|诸葛|东方|赫连|皇甫|尉迟|申屠';
    private $cn_one_name = '赵|钱|孙|李|周|吴|郑|王|冯|陈|褚|卫|蒋|沈|韩|杨|朱|秦|尤|许|何|吕|施|张|孔|曹|严|华|金|魏|陶|姜|戚|谢|邹|喻|柏|水|窦|章|云|苏|潘|葛|奚|范|彭|郎|鲁|韦|昌|马|苗|凤|花|方|俞|任|袁|柳|酆|鲍|史|唐|费|廉|岑|薛|雷|贺|倪|汤|滕|殷|罗|毕|郝|邬|安|常|乐|于|时|傅|皮|卡|齐|康|伍|余|元|卜|顾|孟|平|黄|穆|萧|尹|姚|邵|堪|汪|祁|毛|禹|狄|米|贝|明|臧|计|伏|成|戴|谈|宋|茅|庞|熊|纪|舒|屈|项|祝|董|粱|杜|阮|蓝|闵|席|季|麻|强|贾|路|娄|危|江|童|颜|郭|梅|盛|林|刁|钟|徐|邱|骆|高|夏|蔡|田|樊|胡|凌|霍|虞|万|支|柯|咎|管|卢|莫|经|房|裘|缪|干|解|应|宗|宣|丁|贲|邓|郁|单|杭|洪|包|诸|左|石|崔|吉|钮|龚|程|嵇|邢|滑|裴|陆|荣|翁|荀|羊|於|惠|甄|魏|加|封|芮|羿|储|靳|汲|邴|糜|松|井|段|富|巫|乌|焦|巴|弓|牧|隗|谷|车|侯|宓|蓬|全|郗|班|仰|秋|仲|伊|宫|宁|仇|栾|暴|甘|钭|厉|戎|祖|武|符|刘|姜|詹|束|龙|叶|幸|司|韶|郜|黎|蓟|薄|印|宿|白|怀|蒲|台|从|鄂|索|咸|籍|赖|卓|蔺|屠|蒙|池|乔|阴|郁|胥|能|苍|双|闻|莘|党|翟|谭|贡|劳|逄|姬|申|扶|堵|冉|宰|郦|雍|郤|璩|桑|桂|濮|牛|寿|通|边|扈|燕|冀|郏|浦|尚|农|温|别|庄|晏|柴|翟|阎|充|慕|连|茹|习|宦|艾|鱼|容|向|古|易|慎|戈|廖|庚|终|暨|居|衡|步|都|耿|满|弘|匡|国|文|寇|广|禄|阙|东|殴|殳|沃|利|蔚|越|夔|隆|师|巩|厍|聂|晁|勾|敖|融|冷|訾|辛|阚|那|简|饶|空|曾|沙|须|丰|巢|关|蒯|相|查|后|江|游|竺';

    public function __construct($loaddic = TRUE)
    {
        if($loaddic)
        {
            $onename = explode('|', $this->cn_one_name);

            foreach ($onename as $n)
            {
                $this->one_name_dic[$n] = 1;
            }

            $twoname = explode('|', $this->cn_two_name);

            foreach ($twoname as $n)
            {
                $this->two_name_dic[$n] = 1;
            }

            unset($onename, $twoname);

            $dicfile = APPPATH.'libraries/Words/dict.csv';
            $fp      = fopen($dicfile, 'r');

            while ($line = fgets($fp, 64))
            {
                $ws = explode(' ', $line);
                $this->rank_dic[strlen($ws[0])][$ws[0]] = $ws[1];
            }

            fclose($fp);
        }
    }

    private function _get_source($str)
    {
        $str = iconv('UTF-8', 'GBK', $str);
        $this->source_string = $str;
        $this->result_string = '';
    }

    private function _simple_split($str)
    {
        $this->source_string = $this->_revise_string($str);
        return $this->source_string;
    }

    public function split_result($str = '', $try_num_name = true, $try_diff = true)
    {
        $str = trim($str);

        if($str != '')
        {
            $this->_get_source($str);
        }
        else
        {
            return '';
        }

        $this->source_string = preg_replace('/ {1,}/', ' ', $this->_revise_string($this->source_string));
        $spwords             = explode(' ', $this->source_string);
        $spLen               = count($spwords) - 1;
        $spc                 = $this->split_char;

        for ($i = $spLen; $i >= 0; $i--)
        {
            if(ord($spwords[$i][0]) < 33)
            {
                continue;
            }
            elseif( ! isset($spwords[$i][$this->min_len]))
            {
                $this->result_string = $spwords[$i] . $spc . $this->result_string;
            }
            elseif(ord($spwords[$i][0]) < 0x81)
            {
                $this->result_string = $spwords[$i] . $spc . $this->result_string;
            }
            else
            {
                $this->result_string = $this->_split_mm($spwords[$i], $try_num_name, $try_diff) . $spc . $this->result_string;
            }
        }

        $okstr = iconv('GBK', 'UTF-8', $this->result_string);
        return $okstr;
    }

    private function _par_number($str)
    {
        if($str == '')
        {
            return '';
        }

        $ws    = explode(' ', $str);
        $wlen  = count($ws);
        $spc   = $this->split_char;
        $reStr = '';

        for ($i = 0; $i < $wlen; $i++)
        {
            if($ws[$i] == '')
            {
                continue;
            }

            if($i >= $wlen - 1)
            {
                $reStr .= $spc . $ws[$i];
            }
            else
            {
                $reStr .= $spc . $ws[$i];
            }
        }

        return $reStr;
    }

    private function _par_other($word_array)
    {
        $wlen  = count($word_array) - 1;
        $rsStr = '';
        $spc   = $this->split_char;

        for ($i = $wlen; $i >= 0; $i--)
        {
            if(preg_match('/' . $this->cn_sg_num . '/', $word_array[$i]))
            {
                $rsStr .= $spc . $word_array[$i];

                if($i > 0 && preg_match('/^' . $this->common_unit . '/', $word_array[$i - 1]))
                {
                    $rsStr .= $word_array[$i - 1];
                    $i--;
                }
                else
                {
                    while ($i > 0 && preg_match('/' . $this->cn_sg_num . '/', $word_array[$i - 1]))
                    {
                        $rsStr .= $word_array[$i - 1];
                        $i--;
                    }
                }

                continue;
            }

            if(strlen($word_array[$i]) == 4 && isset($this->two_name_dic[$word_array[$i]]))
            {
                $rsStr .= $spc . $word_array[$i];

                if($i > 0 && strlen($word_array[$i - 1]) == 2)
                {
                    $rsStr .= $word_array[$i - 1];
                    $i--;

                    if($i > 0 && strlen($word_array[$i - 1]) == 2)
                    {
                        $rsStr .= $word_array[$i - 1];
                        $i--;
                    }
                }
            }
            elseif(strlen($word_array[$i]) == 2 && isset($this->one_name_dic[$word_array[$i]]))
            {
                $rsStr .= $spc . $word_array[$i];

                if($i > 0 && strlen($word_array[$i - 1]) == 2)
                {
                    if(preg_match('/' . $this->especial_char . '/', $word_array[$i - 1]))
                    {
                        continue;
                    }
                    else
                    {
                        $rsStr .= $word_array[$i - 1];
                        $i--;
                    }

                    if($i > 0 && strlen($word_array[$i - 1]) == 2 && !preg_match('/' . $this->especial_char . '/', $word_array[$i - 1]))
                    {
                        $rsStr .= $word_array[$i - 1];
                        $i--;
                    }
                }
            }
            else
            {
                $rsStr .= $spc . $word_array[$i];
            }
        }

        $rsStr = preg_replace('/^' . $spc . '/', '', $rsStr);
        return $rsStr;
    }

    private function _split_mm($str, $try_num_name = true, $try_diff = true)
    {
        $spc        = $this->split_char;
        $spLen      = strlen($str);
        $rsStr      = $okWord = $tmpWord = '';
        $word_array = array();

        for ($i = ($spLen - 1); $i >= 0;)
        {
            if($i <= $this->min_len)
            {
                if($i == 1)
                {
                    $word_array[] = substr($str, 0, 2);
                }
                else
                {
                    $w = substr($str, 0, $this->min_len + 1);

                    if($this->_is_word($w))
                    {
                        $word_array[] = $w;
                    }
                    else
                    {
                        $word_array[] = substr($str, 2, 2);
                        $word_array[] = substr($str, 0, 2);
                    }
                }

                $i = -1;
                break;
            }

            if($i >= $this->max_len)
            {
                $max_pos = $this->max_len;
            }
            else
            {
                $max_pos = $i;
            }

            $isMatch = false;

            for ($j = $max_pos; $j >= 0; $j = $j - 2)
            {
                $w = substr($str, $i - $j, $j + 1);

                if($this->_is_word($w))
                {
                    $word_array[] = $w;
                    $i            = $i - $j - 1;
                    $isMatch      = true;
                    break;
                }
            }

            if( ! $isMatch)
            {
                if($i > 1)
                {
                    $word_array[] = $str[$i - 1] . $str[$i];
                    $i            = $i - 2;
                }
            }
        } //End For

        if($try_num_name)
        {
            $rsStr = $this->_par_other($word_array);
        }
        else
        {
            $wlen = count($word_array) - 1;

            for ($i = $wlen; $i >= 0; $i--)
            {
                $rsStr .= $spc . $word_array[$i];
            }
        }

        if($try_diff)
        {
            $rsStr = $this->_test_diff(trim($rsStr));
        }

        return $rsStr;
    }

    private function _auto_description($str, $keyword, $strlen)
    {
        $this->source_string = $this->_revise_string($this->source_string);
        $spwords             = explode(' ', $this->source_string);
        $keywords            = explode(' ', $this->keywords);
        $regstr              = '';

        foreach ($keywords as $k => $v)
        {
            if($v == '')
            {
                continue;
            }

            if(ord($v[0]) > 0x80 && strlen($v) < 3)
            {
                continue;
            }

            if($regstr == '')
            {
                $regstr .= '($v)';
            }
            else
            {
                $regstr .= '|($v)';
            }
        }
    }

    private function _test_diff($str)
    {
        $str = preg_replace('/ {1,}/', ' ', $str);

        if($str == '' || $str == ' ')
        {
            return '';
        }

        $ws    = explode(' ', $str);
        $wlen  = count($ws);
        $spc   = $this->split_char;
        $reStr = '';

        for ($i = 0; $i < $wlen; $i++)
        {
            if($i >= ($wlen - 1))
            {
                $reStr .= $spc . $ws[$i];
            }
            else
            {
                if($ws[$i] == $ws[$i + 1])
                {
                    $reStr .= $spc . $ws[$i] . $ws[$i + 1];
                    $i++;
                    continue;
                }

                if(strlen($ws[$i]) == 2 && strlen($ws[$i + 1]) < 8 && strlen($ws[$i + 1]) > 2)
                {
                    $addw   = $ws[$i] . $ws[$i + 1];
                    $t      = 6;
                    $testok = false;

                    while ($t >= 4)
                    {
                        $w = substr($addw, 0, $t);

                        if($this->_is_word($w) && ($this->_get_rank($w) > $this->_get_rank($ws[$i + 1]) * 2))
                        {
                            $limit_word = substr($ws[$i + 1], strlen($ws[$i + 1]) - $t - 2, strlen($ws[$i + 1]) - strlen($w) + 2);

                            if($limit_word != '')
                            {
                                $reStr .= $spc . $w . $spc . $limit_word;
                            }
                            else
                            {
                                $reStr .= $spc . $w;
                            }

                            $testok = true;
                            break;
                        }

                        $t = $t - 2;
                    }

                    if( ! $testok)
                    {
                        $reStr .= $spc . $ws[$i];
                    }
                    else
                    {
                        $i++;
                    }
                }
                elseif(strlen($ws[$i]) > 2 && strlen($ws[$i]) < 8 && strlen($ws[$i + 1]) > 2 && strlen($ws[$i + 1]) < 8)
                {
                    $t21 = substr($ws[$i + 1], 0, 2);
                    $t22 = substr($ws[$i + 1], 0, 4);

                    if($this->_is_word($ws[$i] . $t21))
                    {
                        if(strlen($ws[$i]) == 6 || strlen($ws[$i + 1]) == 6)
                        {
                            $reStr .= $spc . $ws[$i] . $t21 . $spc . substr($ws[$i + 1], 2, strlen($ws[$i + 1]) - 2);
                            $i++;
                        }
                        else
                        {
                            $reStr .= $spc . $ws[$i];
                        }
                    }
                    elseif(strlen($ws[$i + 1]) == 6)
                    {
                        if($this->_is_word($ws[$i] . $t22))
                        {
                            $reStr .= $spc . $ws[$i] . $t22 . $spc . $ws[$i + 1][4] . $ws[$i + 1][5];
                            $i++;
                        }
                        else
                        {
                            $reStr .= $spc . $ws[$i];
                        }
                    }
                    elseif(strlen($ws[$i + 1]) == 4)
                    {
                        $addw   = $ws[$i] . $ws[$i + 1];
                        $t      = strlen($ws[$i + 1]) - 2;
                        $testok = false;

                        while ($t > 0)
                        {
                            $w = substr($addw, 0, strlen($ws[$i]) + $t);

                            if($this->_is_word($w) && ($this->_get_rank($w) > $this->_get_rank($ws[$i + 1]) * 2))
                            {
                                $limit_word = substr($ws[$i + 1], $t, strlen($ws[$i + 1]) - $t);

                                if($limit_word != '')
                                {
                                    $reStr .= $spc . $w . $spc . $limit_word;
                                }
                                else
                                {
                                    $reStr .= $spc . $w;
                                }

                                $testok = true;
                                break;
                            }

                            $t = $t - 2;
                        }

                        if( ! $testok)
                        {
                            $reStr .= $spc . $ws[$i];
                        }
                        else
                        {
                            $i++;
                        }
                    }
                    else
                    {
                        $reStr .= $spc . $ws[$i];
                    }
                }
                else
                {
                    $reStr .= $spc . $ws[$i];
                }
            }
        }

        return $reStr;
    }

    private function _is_word($okWord)
    {
        $slen = strlen($okWord);

        if($slen > $this->max_len)
        {
            return false;
        }
        else
        {
            return isset($this->rank_dic[$slen][$okWord]);
        }
    }

    private function _revise_string($str)
    {
        $spc  = $this->split_char;
        $slen = strlen($str);

        if($slen == 0)
        {
             return '';
        }

        $okstr   = '';
        $prechar = 0; // 0-空白 1-英文 2-中文 3-符号

        for ($i = 0; $i < $slen; $i++)
        {
            if(ord($str[$i]) < 0x81)
            {
                if(ord($str[$i]) < 33)
                {
                    if($prechar != 0)
                    {
                        $okstr .= $spc;
                    }

                    $prechar = 0;
                    continue;
                }
                elseif(preg_match('/[^0-9a-zA-Z@\.%#:\\/\\&_-]/', $str[$i]))
                {
                    if($prechar == 0)
                    {
                        $okstr .= $str[$i];
                        $prechar = 3;
                    }
                    else
                    {
                        $okstr .= $spc . $str[$i];
                        $prechar = 3;
                    }
                }
                else
                {
                    if($prechar == 2 || $prechar == 3)
                    {
                        $okstr .= $spc . $str[$i];
                        $prechar = 1;
                    }
                    else
                    {
                        if(preg_match('/@#%:/', $str[$i]))
                        {
                            $okstr .= $str[$i];
                            $prechar = 3;
                        }
                        else
                        {
                            $okstr .= $str[$i];
                            $prechar = 1;
                        }
                    }
                }
            }
            else
            {
                if($prechar != 0 && $prechar != 2)
                {
                    $okstr .= $spc;
                }

                if(isset($str[$i + 1]))
                {
                    $c = $str[$i] . $str[$i + 1];

                    if(preg_match('/' . $this->cn_number . '/', $c))
                    {
                        $okstr .= $this->_get_alab_num($c);
                        $prechar = 2;
                        $i++;
                        continue;
                    }

                    $n = hexdec(bin2hex($c));

                    if($n > 0xA13F && $n < 0xAA40)
                    {
                        if($c == '《')
                        {
                            if($prechar != 0)
                            {
                                 $okstr .= $spc . ' 《';
                            }
                            else
                            {
                                $okstr .= ' 《';
                            }

                            $prechar = 2;
                        } elseif($c == '》')
                        {
                            $okstr .= '》 ';
                            $prechar = 3;
                        }
                        else
                        {
                            if($prechar != 0)
                            {
                                $okstr .= $spc . $c;
                            }
                            else
                            {
                                 $okstr .= $c;
                            }

                            $prechar = 3;
                        }
                    }
                    else
                    {
                        $okstr .= $c;
                        $prechar = 2;
                    }

                    $i++;
                }
            }
        }

        return $okstr;
    }

    private function _find_new_word($str, $maxlen = 6)
    {
        $okstr = '';
        return $str;
    }

    public function get_keyword($str, $ilen = -1)
    {
        if($str == '')
        {
            return '';
        }
        else
        {
            $this->split_result($str, true, true);
        }

        $okstr = $this->result_string;
        $ws    = explode(' ', $okstr);

        $okstr = $wks = '';

        foreach ($ws as $w)
        {
            $w = trim($w);

            if(strlen($w) < 2)
            {
                 continue;
            }

            if( ! preg_match('/[^0-9:-]/', $w))
            {
                 continue;
            }

            if(strlen($w) == 2 && ord($w[0]) > 0x80)
            {
                continue;
            }

            if(isset($wks[$w]))
            {
                $wks[$w]++;
            }
            else
            {
                $wks[$w] = 1;
            }
        }

        $wks = array_reverse($wks);

        if(is_array($wks))
        {
            arsort($wks);

            if($ilen == -1)
            {
                foreach ($wks as $w => $v)
                {
                    if($this->_get_rank($w) > 500)
                    {
                        $okstr .= $w . ' ';
                    }
                }
            }
            else
            {
                foreach ($wks as $w => $v)
                {
                    if((strlen($okstr) + strlen($w) + 1) < $ilen)
                    {
                        $okstr .= $w . ' ';
                    }
                    else
                    {
                        break;
                    }
                }
            }
        }

        $okstr = iconv('GBK', 'UTF-8', $okstr);
        return trim($okstr);
    }

    private function _get_rank($w)
    {
        if(isset($this->rank_dic[strlen($w)][$w]))
        {
            return $this->rank_dic[strlen($w)][$w];
        }
        else
        {
            return 0;
        }
    }

    private function _get_alab_num($fnum = '')
    {
        $nums  = array(
            '０','１','２','３','４','５','６','７','８','９','＋','－',
            '％','．','ａ','ｂ','ｃ','ｄ','ｅ','ｆ','ｇ','ｈ','ｉ','ｊ',
            'ｋ','ｌ','ｍ','ｎ','ｏ','ｐ','ｑ','ｒ','ｓ ','ｔ','ｕ','ｖ',
            'ｗ','ｘ','ｙ','ｚ','Ａ','Ｂ','Ｃ','Ｄ','Ｅ','Ｆ','Ｇ','Ｈ',
            'Ｉ','Ｊ','Ｋ','Ｌ','Ｍ','Ｎ','Ｏ','Ｐ','Ｑ','Ｒ','Ｓ','Ｔ',
            'Ｕ','Ｖ','Ｗ','Ｘ','Ｙ','Ｚ'
        );

        $fnums = '0123456789+-%.abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $fnum  = str_replace($nums, $fnums, $fnum);
        return $fnum;
    }

	public function __destory()
    {
        unset($this->rank_dic);
    }
}