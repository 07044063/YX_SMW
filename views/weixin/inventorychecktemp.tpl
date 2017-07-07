{section name=oi loop=$inventorylist}
    <div style="margin-top: 8px; margin-bottom: 8px">
        <div>
            <span class="text-subtitle">{$inventorylist[oi].goods_ccode}</span>
            <span class="text-normal">{$inventorylist[oi].goods_name}</span>
        </div>
        <div>
            <span class="text-normal">良品：{$inventorylist[oi].quantity}</span>
            <span class="text-normal">冻结：{$inventorylist[oi].locked}</span>
            <span class="text-normal">不良品：{$inventorylist[oi].abnormal}</span>
        </div>
    </div>
    <hr/>
{/section}