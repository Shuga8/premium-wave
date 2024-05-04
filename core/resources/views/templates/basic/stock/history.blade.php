@php
    $meta = (object) $meta;
    $pair = @$meta->pair;
@endphp
<div class="trading-right__bottom">
    <div class="trading-history">
        <h5 class="trading-history__title"> @lang('Trade History') </h5>
    </div>
    <div class="d-flex trading-market__header justify-content-between text-center">
        <div class="trading-market__header-two">
            @lang('Price')()
        </div>
        <div class="trading-market__header-one">
            @lang('Amount') ()
        </div>
        <div class="trading-market__header-three">
            @lang('Date/Time')
        </div>
    </div>
    <div class="tab-content" id="pills-tabContentfortyfour">
        <div class="tab-pane fade show active" id="pills-marketnineteen" role="tabpanel"
            aria-labelledby="pills-marketnineteen-tab" tabindex="0">
            <div class="market-wrapper">
                <div class="history  trade-history"></div>
            </div>
        </div>
    </div>
</div>

@if (!app()->offsetExists('trade_script'))
@php app()->offsetSet('trade_script',true) @endphp
@push('script')
    
@endpush
@endif
