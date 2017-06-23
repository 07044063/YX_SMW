{section name=oi loop=$returnings}
    {*<div class="weui-media-box weui-media-box_text" href="javascript:;">*}
    {*<div class="weui-media-box__hd">*}
    {*<img class="weui-media-box__thumb" style="max-height: 50px" src="{$returnings[oi].pic_url}" alt="">*}
    {*</div>*}
    {*<div class="weui-media-box__bd">*}
    {*<h4 class="weui-media-box__title">退货单号：{$returnings[oi].returning_code}*}
    {*&nbsp;{$returnings[oi].statusX}</h4>*}
    {*<p class="weui-media-box__desc">创建人：{$returnings[oi].person_name}</p>*}
    {*<p class="weui-media-box__desc">备注：{$returnings[oi].remark}</p>*}
    {*<ul class="weui-media-box__info">*}
    {*<li class="weui-media-box__info__meta">来源</li>*}
    {*<li class="weui-media-box__info__meta">时间</li>*}
    {*<li class="weui-media-box__info__meta weui-media-box__info__meta_extra">其它信息</li>*}
    {*</ul>*}
    {*</div>*}
    {*</div>*}
    <div class="weui-media-box weui-media-box_appmsg">
        <a href="javascript:viewPic('{$returnings[oi].pic_url}');">
            <div class="weui-media-box__hd">
                <img class="weui-media-box__thumb" src="{$returnings[oi].pic_url}" alt="">
            </div>
        </a>
        <a {if $returnings[oi].status = 'create'} href="javascript:doReceive({$returnings[oi].id});" {/if}>
            <div class="weui-media-box__bd">
                <p class="text-subtitle">退货单号：{$returnings[oi].returning_code}
                    &nbsp;{$returnings[oi].statusX}</p>
                <p class="text-normal">创建人：{$returnings[oi].person_name}</p>
                <p class="text-normal">备注：{$returnings[oi].remark}</p>
            </div>
        </a>
    </div>
{/section}