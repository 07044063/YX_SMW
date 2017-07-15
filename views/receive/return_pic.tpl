{include file='../__header_v2.tpl'}
{assign var="script_name" value="return_pic_controller"}

<div class="pd15" align="center" ng-controller="returnpicController" ng-app="ngApp">

    <input class="text hidden" id="returnpic_id" value="{$returnpic_id}"/>

    {literal}
        <div class="row"><h4>点击图片可旋转</h4></div>
        <div id="return_div" style="margin-top: 10px">
            <a href="javascript:; " ng-click="imgRotate()">
                <img id="return_pic"
                     style="margin-top: 10px;max-width: 1100px;max-height: 1100px"
                     src="{{returnpic_url}}" alt="退库图片"/>
            </a>
        </div>
    {/literal}
</div>

<script type="text/javascript" src="{$docroot}static/script/receive/{$script_name}.js?v={$cssversion}"></script>

{include file='../__footer_v2.tpl'}
