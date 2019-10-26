<h1><?=$data['view_title']?></h1>
<form method="GET" action="<?=current_url()?>">
 <label class="select">
  <select name="lang" onchange="submit()">
   <option>Выберите язык</option>
   <?php foreach($conf['langs'] as $v){?>
   <option value="<?=$v['tag']?>"><?="{$v['title']} [{$v['tag']}]"?></option>
   <?php }?>
  </select>
 </label>
</form>