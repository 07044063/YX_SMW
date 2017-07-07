{section name=oi loop=$orders}
    <div style="margin-top: 8px; margin-bottom: 8px">
        <a href="javascript:goDetail('{$orders[oi].order_code}');">
            <div>
                <span class="text-subtitle">{$orders[oi].order_type}：{$orders[oi].order_serial_no}</span>
                <span class="text-subtitle" style="float: right;margin-right: 10px">{$orders[oi].statusX}</span>
            </div>
            <p class="text-normal">需求时间：{$orders[oi].order_date}</p>
            <div>
                <span class="text-normal">{$orders[oi].vendor_shortname}</span>
                <span class="text-normal" style="float: right;margin-right: 10px">{$orders[oi].address}</span>
            </div>
        </a>
    </div>
    <hr/>
{/section}