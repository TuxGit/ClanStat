<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2011-10-24 11:54:02 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd, Exinaus, Shw  $
    * @copyright   2011-2012 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.2.0 $
    *
    */
?>
<?php
    error_reporting(E_ALL & ~E_STRICT);
    ini_set("display_errors", 1);
    if (file_exists(dirname(__FILE__).'/function/mysql.php')) {
        define('ROOT_DIR', dirname(__FILE__));
    }else{
        define('ROOT_DIR', '.');    
    }
    //Starting script time execution timer
    $begin_time = microtime(true);

    //Cheker
    include_once(ROOT_DIR.'/including/check.php');

    //MYSQL
    include_once(ROOT_DIR.'/function/mysql.php');

    //Multiget CURL
    include_once(ROOT_DIR.'/function/curl.php');
    include_once(ROOT_DIR.'/function/mcurl.php');

    // Include Module functions
    include_once(ROOT_DIR.'/function/rating.php');
    include_once(ROOT_DIR.'/function/func.php');
    include_once(ROOT_DIR.'/function/func_main.php');
    include_once(ROOT_DIR.'/function/func_get.php');
    include_once(ROOT_DIR.'/function/func_gk.php');

    // Including main config files
    include_once(ROOT_DIR.'/function/config.php');
    include_once(ROOT_DIR.'/config/config_'.$config['server'].'.php');

    //Loding language pack
    foreach(scandir(ROOT_DIR.'/translate/') as $files){
        if (preg_match ("/_".$config['lang'].".php/", $files)){
            include_once(ROOT_DIR.'/translate/'.$files);
        }
    }
    $battel = array();

    if(is_valid_url($config['td']) == true){
        $battel = get_clan_attack($config,$config['clan']);
    }

    //include_once(ROOT_DIR.'/views/header.php');
?>
<script type="text/javascript" id="js">
   $(document).ready(function()
   {
       $("#attack").tablesorter({sortList:[[1,0]]});
   });
</script>
<div align="center">
    <table id="attack" cellspacing="1" cellpadding="2" width="100%">
        <thead>
            <tr>
                <th width="40"><?=$lang['type']; ?></th>
                <th><?=$lang['time']; ?></th>
                <th><?=$lang['province']; ?></th>

            </tr>
        </thead>
        <tbody>
            <?php if (isset($battel['request_data'])){
                    foreach ($battel['request_data']['items'] as $val){
                        if (strlen($val['time']) > 1){
                            $date = date('H:i',$val['time']);
                        } else {
                            $date = '--:--';
                        }
                        if ($val['type'] == 'landing'){
                            $type = '<img src="./images/landing.png">';
                        } elseif ($val['type'] == 'for_province'){
                            $type = '<img src="./images/attacked.png">';
                        } elseif ($val['type'] == 'meeting_engagement'){
                            $type = '<img src="./images/combats_running.png">';
                        };
                    ?>
                    <tr>
                        <td align="center"><?=$type; ?></td>
                        <td><?=$date; ?></td>
                        <td><a href="<?=$config['clan_link']; ?>maps/?province=<?=$val['provinces'][0]['id']; ?>" target="_blank"><?=$val['provinces'][0]['name']; ?></a></td>
                    </tr>
                    <?php };
                    if ((isset($battel['request_data'])) && (count($battel['request_data']['items']) == 0 )) {  ?>
                    <tr>
                        <td colspan="3" align="center"><?=$lang['no_war']; ?></td>
                    </tr>
                    <?php
                    };
                } else { ?>
                <tr>
                    <td colspan="3" align="center"><?=$lang['error_1']?></td>
                </tr>
                <?php }; ?>
        </tbody>
    </table>
</div>