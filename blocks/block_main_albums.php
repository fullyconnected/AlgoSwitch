<?php
if(!isset($myuser_id) || empty ($myuser_id)){
$tpl->set_file('outmenu','menu/outmenu.html');
$tpl->set_var('relative_path', $relative_path);
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('sitename',_SITENAME_);
$outmenu=$tpl->process('out','outmenu',0,1);
}

else {

$tpl->set_file('outmenu','menu/outmenu_in.html');
$tpl->set_var('relative_path', $relative_path);
$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('sitename',_SITENAME_);
$outmenu=$tpl->process('out','outmenu',0,1);

}

$tpl->set_file('frame','frame.html');
$tpl->set_var('title',$title);


$tpl->set_var('baseurl',_BASEURL_);
$tpl->set_var('sitename',_SITENAME_);
$tpl->set_var('relative_path', $relative_path);
$tpl->set_var('tplurl',_TPLURL_.'/'.get_my_template());
$tpl->set_var('outmenu',$outmenu);
$tpl->set_file('metatags','blocks/block_metatags.html');
$metatags=$tpl->process('out','metatags',0,1);
$tpl->set_var('metacontent',$metatags);

$tpl->set_var('middle_content',$middle_content);

print $tpl->process('out','frame',0,1);

?>
