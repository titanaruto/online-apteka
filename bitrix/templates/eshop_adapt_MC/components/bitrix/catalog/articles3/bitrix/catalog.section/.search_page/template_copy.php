<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>


    <div class="item active">
	   <div class="row">
	       <div class="col-sm-12">
	              <?
					foreach ($arResult as $item){
						?><div style="float: left;height: 200px; margin: 10px; width: 200px; background: red;">
							<h2><?=$item['NAME']?></h2>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid consectetur dolor nobis ipsa molestias blanditiis fuga quod! .</p>
						</div><?
					}
				 ?>    
		    </div>
       </div><!--/row-->
	</div><!--/item-->
	
	
	
		
		
		  

		
		
				