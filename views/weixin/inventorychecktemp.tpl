{section name=oi loop=$inventorylist}
    <div class="weui-media-box weui-media-box_text" href="javascript:;">
        <p class="text-subtitle">图号：{$inventorylist[oi].goods_ccode}：良品：{$inventorylist[oi].quantity}
            &nbsp;冻结：{$inventorylist[oi].locked}&nbsp;不良品：{$inventorylist[oi].abnormal}</p>
        <p class="text-normal">名称：{$inventorylist[oi].goods_name}</p>
    </div>
{/section}