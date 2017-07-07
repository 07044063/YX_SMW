{section name=oi loop=$orders}
    <div class="weui-media-box weui-media-box_text" href="javascript:;">
        <a href="javascript:goDetail('{$orders[oi].order_code}');">
            <p class="text-subtitle">{$orders[oi].order_type}：{$orders[oi].order_serial_no}</p>
            <p class="text-subtitle" style="float: right">{$orders[oi].statusX}</p>
            <p class="text-normal">需求时间：{$orders[oi].order_date}</p>
            <p class="text-normal">供应商：{$orders[oi].vendor_name}</p>
        </a>
    </div>
{/section}