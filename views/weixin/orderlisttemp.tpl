{section name=oi loop=$orders}
    <a href="javascript:goDetail('{$orders[oi].order_code}');" class="weui-cell weui-cell_access weui-cell_link">
        <div class="weui-media-box weui-media-box_text" href="javascript:;">
            <p class="text-subtitle">{$orders[oi].order_type}：{$orders[oi].order_serial_no}
                &nbsp;{$orders[oi].statusX}</p>
            <p class="text-normal">需求时间：{$orders[oi].order_date}</p>
            <p class="text-normal">供应商：{$orders[oi].vendor_name}</p>
        </div>
    </a>
{/section}