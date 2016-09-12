<?php 
namespace App\Utils;

use Goutte\Client;
use App\Utils\strException;
use App\Model\User_Wechat;
use App\Model\Grade;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Form;
use Carbon\Carbon;

class Grab
{   
    //根据学号密码登录并抓取本学期成绩页面
    protected function getHtml($studentId, $password)
    {
        $client = new Client; 

        $dom = new \DOMDocument();
        $dom->loadHtml('<html>
                        <form method="POST" action="http://jw.zzu.edu.cn/scripts/qscore.dll/search">
                        <input type="text" name="nianji">
                        <input type="text" name="xuehao">
                        <input type="text" name="mima">
                        <input type="submit" name="submit">
                      </form>
                    </html>'); 

        $nodes = $dom->getElementsByTagName('form');
        $form = new Form($nodes->item(0), 'http://example.com/login/');
        $form->setValues(array(
             'nianji' => substr($studentId, 0, 4),
             'xuehao' => $studentId,
             'mima' => $password
        ));

        $crawler = $client->submit($form);

        return $crawler;  
    }

    //根据所抓取的页面获取学生个人信息
    public function getInfo($studentId, $password)
    {   

        $html = $this->getHtml($studentId, $password);
        if($html != 'err')
        {
            $result = $html->filter('body > font');
            
            //成绩页面的学生信息详情，错误页面没有为空
            if($result->count())
            {
                $info = $result->eq(0)->text();
                //取学生信息的相关正则
                $r_name = '/(?<=\姓名：).+?(?=\班级)/';
                $r_major = '/(?<=\专业：).+(?=\学期)/';
       
                preg_match($r_name, $info, $name);
                preg_match($r_major, $info, $major);
                
                $studentInfo = array(
                    'userName' => substr($name[0], 0, -4),
                    'major' => substr($major[0], 0, -4),
                    //'term' => $term[0]   
               );
                return $studentInfo;
              }
                }   else {
           
            return 'err';    
    
            } 
        
    }

    //获取其他学期成绩页面
    protected function otherGrade($html, $term)
    {   
        $links = $html->filter('a')->each(function(Crawler $node, $i){   
            return $node->link()->getUri();
         });
        
        $client = new Client; 
        return $client->request('GET', $links[$term]);
    }

    //解除绑定
    public function unBind($openId)
    {
       $user = User_Wechat::find($openId);   
        
        if(!$user) return '还未绑定';
        
        if($user->delete()) 
        {
           return '解除绑定成功';
        }
    }

    //恢复绑定 
   public  function reBind($openId)
    {
        $user =  User_Wechat::onlyTrashed()->where('openId', $openId);
        
        if($user->first())
        {
            $user->restore();
            return '恢复绑定成功';
        } else {

            return '该命令仅限于恢复绑定';
        }
    }   

    //根据查询条件抓取指定term的成绩信息
   public function getGrade($openId, $term)
    {
        //获取用户信息
        $user = User_Wechat::find($openId);
        if(!$user){
            return false;
        }
        $studentId = $user->studentId;
        $password = $user->password;
       
        $now = Carbon::now();

         //根据学期查成绩，term为空查询最新成绩
        if($term!=null && preg_match("/^\d*$/", $term))
        { 
            //比对当前成绩信息的更新时间 
            $grade = $user->grade()->where('term', $term)->first();           
             if($grade != null)
            {
                //两个小时内只获取数据库信息
                if($now->subHour(5)->lte($grade->updated_at))
                { 
                  return $this->arrToStr(json_decode(($grade->items),true));
                }
               
             }
    
            $html = $this->getHtml($studentId, $password);
             //检查网络和登录失败情况
            is_null($html) && function(){return '网络繁忙';};
           // if(!$this->validation($html)) return '信息有误，请尝试修改绑定';
            
            $validation= $this->validation($html);
            if($validation[0]) return $validation[1]; 
            
            $otherGrade = $this->otherGrade($html, $term); 
            return $this->checkGrade($otherGrade, $user, $term);
        
        } else {
                            
            //比对当前成绩信息的更新时间
            //判断当前假期月份
            if($now->month>6)
            {
              $term = (config('grade.year')-$user->year)*2;
            } else {
              $term = (config('grade.year')-$user->year)*2-1;
            }
            
            //$grade = $user->grade()->orderBy('term')->get()->last();           
            $grade = $user->grade()->where('term', $term)->first();           
             if($grade != null)
            {   
                //两个小时内只获取数据库信息
                if($now->subMinutes(10)->lte($grade->updated_at))
                { 
                  return $this->arrToStr(json_decode(($grade->items),true));
                }
               
             }
            
            $html = $this->getHtml($studentId, $password);
            
            //检查网络和登录失败情况
            is_null($html) && function(){return '网络繁忙';};
           // if(!$this->validation($html)) return '信息有误，请尝试修改绑定';
            $validation= $this->validation($html);
            if($validation[0]==1) return $validation[1]; 
             
            return $this->checkGrade($html, $user);            
        }
    }


    //解析页面获得成绩信息并保存
    protected function checkGrade($html, $user, $term = null)
    {
        if($html->filter('td')->count() > 4)
        {   
            //获取成绩
           $results = $html
                        ->filter('tr')
                        ->siblings()
                        ->each(function (Crawler $node, $i) {
                            $items['课程：'] = $node->filter('td')->eq('0')->text()."\n";
                            $items['成绩：'] = $node->filter('td')->eq('3')->text()."\n";
                            $items['学分：'] = $node->filter('td')->eq('2')->text()."\n";
                            $items['绩点：'] = $node->filter('td')->eq('4')->text()."\n----------------\n";
                            return $items;  
                           }); 
            //没有成绩时
           if($results == null) $results['暂时还没有出成绩~'] = "\n\n"; 
           //获取学期
           $term = $html->filter('body > font')->text();
           $term = substr(trim($term), -1);

           //其他信息(专业排名信息，个人信息)          
           //$rank = $html->filter('blockquote p')->text();
           $text = "(目前只能查询部分已出成绩，成绩信息来源于郑州大学教务在线，如有疑问可登陆核实，欢迎大家反馈意见。)"; 
           $title =  array($user->year.'级 ' => $user->userName.' 第'.$term."学期\n\n");   
           //if($rank)
           //{
              array_push($results, array(""=>trim($text)));           
           //}       
           array_unshift($results, $title);

           //获取当前查询学期成绩
           $db_grade = $user->grade()->where('term', $term)->first();           
            
           //对比更新成绩信息，为空则插入信息 
           if($db_grade)
           {
             //转为JSON格式不使用Unicode编码
             if(strlen($db_grade->items) != strlen(json_encode($results,JSON_UNESCAPED_UNICODE)))
             {  
                $db_grade->items = json_encode($results,JSON_UNESCAPED_UNICODE);
                $db_grade->save();          
             
             } else { 
               //如果成绩信息没有变化，只更新时间戳
                $db_grade->touch();
            }

           } else {  
           
             $grade = new Grade;
             $grade->userId = $user->id;
             $grade->items = json_encode($results,JSON_UNESCAPED_UNICODE);
             $grade->term = $term;
             $grade->save();
           } 
            
           return $this->arrToStr($results);

        } else {
            //抓取成绩失败从数据库读取
           if(is_null($term))
           {
                $grade = $user->grade()->orderBy('term')->get()->last(); 
           } else {
                $grade = $user->grade()->where('term', $term)->first();
           }

           if($grade)
           { 
                return $this->arrToStr(json_decode(($grade->items),true));
           } else {    
                return '查本学期成绩，直接发送‘成绩’两个字';
           }
        }
    
    }
    
    //账号密码是否错误
    protected function validation($html)
    {
        $notice = $html->filter('font')->eq(0)->text();
                
        if(preg_match("/没有找到你/",$notice))
        {
            return array('1','密码错误，请尝试重新绑定,绑定不上到教务网试试（http://jw.zzu.edu.cn/cjcx.html）');
        }
        else if(preg_match("/缴费注册/",$notice))
        {
            return array('2','需要缴费注册什么鬼，教务网出问题啦（http://jw.zzu.edu.cn/cjcx.html）)');
        }
     //   {
     //       return $notice;
     //   }
    }

    //数组转字符串
    protected function arrToStr($array)
    {
          // 定义存储所有字符串的数组
          static $r_arr = array();
          
          if (is_array($array)) {
              foreach ($array as $key => $value) {
                  if (is_array($value)) {
                      // 递归遍历
                     $this-> arrToStr($value);
                  } else {
                      $r_arr[] = $key.$value;
                  }
              }
          } else if (is_string($array)) {
                  $r_arr[] = $array;
          }
         
          //数组去重
          $string = implode('', $r_arr);
          
          return $string;
    }

}
