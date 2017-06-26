{include file="../__header_wx.tpl"}
{assign var="script_name" value="returningcreate"}

<div class="weui-cells__title">创建退货单</div>
<div class="weui-cells weui-cells_form">
    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">退货单号</label></div>
        <div class="weui-cell__bd">
            <input id="r_id" class="weui-input" type="number" pattern="[0-9]*" placeholder="请输入退货单号">
        </div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__hd">
            <label class="weui-label">备注说明</label>
        </div>
        <div class="weui-cell__bd">
            <input id="r_remark" class="weui-input" type="text" placeholder="请输入备注信息">
        </div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <div class="weui-uploader">
                <div class="weui-uploader__hd">
                    <p class="weui-uploader__title">图片上传</p>
                    {*<div id="uploadFilesNumber" class="weui-uploader__info">0/1</div>*}
                </div>
                <div class="weui-uploader__bd">
                    <ul class="weui-uploader__files" id="uploaderFiles">
                        {*<li class="weui-uploader__file" style="background-image:url(static/images/image_error.jpg)"></li>*}
                        {*<li class="weui-uploader__file weui-uploader__file_status"*}
                        {*style="background-image:url(static/images/image_error.jpg)">*}
                        {*<div class="weui-uploader__file-content">*}
                        {*<i class="weui-icon-warn"></i>*}
                        {*</div>*}
                        {*</li>*}
                        {*<li class="weui-uploader__file weui-uploader__file_status"*}
                        {*style="background-image:url(static/images/image_error.jpg)">*}
                        {*<div class="weui-uploader__file-content">50%</div>*}
                        {*</li>*}
                    </ul>
                    <div class="weui-uploader__input-box">
                        <button id="uploaderInput" class="weui-uploader__input" type="input" onclick="chooseImg()">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="margin: 10px">
    <a href="javascript:saveData();" class="weui-btn weui-btn_primary">创建退货单</a>
</div>

<script type="text/javascript" src="{$docroot}static/script/weixin/{$script_name}.js"></script>

{include file="../__footer_wx.tpl"}