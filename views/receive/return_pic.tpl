{include file='../__header_v2.tpl'}
{assign var="script_name" value="return_pic_controller"}

<div class="pd15" align="center" ng-controller="returnpicController" ng-app="ngApp">

    <input class="text hidden" id="returnpic_id" value="{$returnpic_id}"/>

    {literal}
        <div class="row">

        </div>
       <div id="return_div" class="row"  style="width:700px;height: 700px" >
           <img style="width:700px;height: 700px"
                src="{{returnpic_url}}" alt="退库图片"/>
       </div>
        <div class="row">
        <p ></p>
        </div>
        <div class="row">
            <button type="button" class="btn btn-primary" ng-click="imgRotate()"
                    style="width:100px;height:50px;">  旋     转  </button>
        </div>
    {/literal}
</div>


<script src="{$docroot}static/script/lib/select2/select2.full.min.js"></script>

<script type="text/javascript" src="{$docroot}static/script/receive/{$script_name}.js?v={$cssversion}"></script>

{include file='../__footer_v2.tpl'}
