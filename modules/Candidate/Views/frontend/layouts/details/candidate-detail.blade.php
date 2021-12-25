<div class="content-column col-lg-8 col-md-12 col-sm-12">
    <div class="job-detail">
        <h4>{{__('Candidates About')}}</h4>
        {!! clean($row->user->bio) !!}
        <!-- Resume / Education -->

        <div class="related-jobs">
            @if($row->jobCandidate->count() > 0)
            <div class="title-box">
                <h3>{{ __("Jobs Applied") }}</h3>
            </div>
            @endif
            @foreach($row->jobCandidate as $jc)
                @php
                    $data = $jc->jobInfo;
                @endphp
                <div class="job-block">
                    <div class="inner-box">
                        <div class="content">
                            @if($company_logo = $data->getThumbnailUrl())
                                <span class="company-logo">
                                    <img src="{{ $company_logo }}" alt="{{ $data->company ? $data->company->name : 'company' }}">
                                </span>
                            @endif
                            <h4>
                                <a href="{{ $data->getDetailUrl() }}">{{ $translation->title }}</a>
                            </h4>
                            <ul class="job-info">
                                @if($data->category)
                                    <li><span class="icon flaticon-briefcase"></span> {{ $data->category->name }}</li>
                                @endif
                                @if($data->location)
                                    <li><span class="icon flaticon-map-locator"></span> {{ $data->location->name }}</li>
                                @endif
                                @if($data->created_at)
                                    <li><span class="icon flaticon-clock-3"></span> {{ $data->timeAgo() }}</li>
                                @endif
                                @if($data->salary_min && $data->salary_max)
                                    <li><span class="icon flaticon-money"></span> {{ $data->getSalary(false) }}</li>
                                @endif
                            </ul>
                            <ul class="job-other-info">
                                @if($data->jobType)
                                    <li class="time">{{ $data->jobType->name }}</li>
                                @endif
                                @if($data->is_featured)
                                    <li class="privacy">{{ __("Featured") }}</li>
                                @endif
                                @if($data->is_urgent)
                                    <li class="required">{{ __("Urgent") }}</li>
                                @endif
                            </ul>
                                <button class="bookmark-btn @if($data->wishlist) active @endif service-wishlist" data-id="{{$data->id}}" data-type="{{$data->type}}"><span class="flaticon-bookmark"></span></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if(!empty($row->education))
            <div class="resume-outer">
                <div class="upper-title">
                    <h4>{{__('Education')}}</h4>
                </div>
                <div class="my_resume_eduarea">
                    @foreach($row->education as $oneData)
                        <div class="resume-block">
                            <div class="inner">
                                <span class="name">{{@$oneData['location'][0]}}</span>
                                <div class="title-box">
                                    <div class="info-box">
                                        <h3>{{@$oneData['reward']}}</h3>
                                        <span>{{@$oneData['location']}}</span>
                                    </div>
                                    <div class="edit-box">
                                        <span class="year">{{@$oneData['from']}} - {{@$oneData['to']}}</span>
                                    </div>
                                </div>
                                <div class="text">{!! nl2br(strip_tags(@$oneData['information'])) !!}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if(!empty($row->experience))
            <!-- Resume / Work & Experience -->
            <div class="resume-outer theme-blue">
                <div class="upper-title">
                    <h4>{{__('Work & Experience')}}</h4>
                </div>
                @foreach($row->experience as $oneData)
                    <div class="resume-block">
                        <div class="inner">
                            <span class="name">{{@$oneData['location'][0]}}</span>
                            <div class="title-box">
                                <div class="info-box">
                                    <h3>{{@$oneData['position']}}</h3>
                                    <span>{{@$oneData['location']}}</span>
                                </div>
                                <div class="edit-box">
                                    <span class="year">{{@$oneData['from']}} - {{@$oneData['to']}}</span>
                                </div>
                            </div>
                            <div class="text">{!! nl2br(strip_tags(@$oneData['information'])) !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($row->getGallery())
            <!-- Portfolio -->
            <div class="portfolio-outer">
                <div class="row">
                    @foreach($row->getGallery() as $key=>$item)
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <figure class="image">
                                <a href="{{$item['large']}}" class="lightbox-image"><img src="{{$item['thumb']}}" alt=""></a>
                                <span class="icon flaticon-plus"></span>
                            </figure>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if(!empty($row->award))
            <!-- Resume / Awards -->
            <div class="resume-outer theme-yellow">
                <div class="upper-title">
                    <h4>{{__('Awards')}}</h4>
                </div>
                @foreach($row->award as $oneData)
                    <div class="resume-block">
                        <div class="inner">
                            <span class="name"></span>
                            <div class="title-box">
                                <div class="info-box">
                                    <h3>{{@$oneData['reward']}}</h3>
                                    <span></span>
                                </div>
                                <div class="edit-box">
                                    <span class="year">{{@$oneData['from']}} - {{@$oneData['to']}}</span>
                                </div>
                            </div>
                            <div class="text">{!! nl2br(strip_tags(@$oneData['information'])) !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($row->video)
            <!-- Video Box -->
            <div class="video-outer">
                <h4>{{__('Candidates About')}}</h4>
                <div class="video-box">
                    <figure class="image">
                        <a href="{{$row->video}}" class="play-now" data-fancybox="gallery" data-caption="">
                            @if($row->video_cover_id)
                                <img src="{{ get_file_url($row->video_cover_id, 'full') }}" alt="">
                            @else
                                <img src="{{ asset('images/resource/video-img.jpg') }}" alt="">
                            @endif
                            <i class="icon flaticon-play-button-3" aria-hidden="true"></i>
                        </a>
                    </figure>
                </div>
            </div>
        @endif
    </div>
</div>



