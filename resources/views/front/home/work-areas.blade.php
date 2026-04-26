@php
  $L    = app()->getLocale();
  $t    = fn($lo,$en,$zh) => match($L){'zh'=>$zh,'en'=>$en,default=>$lo};

  $areas = [
    ['num'=>'01','icon'=>'fas fa-book-open',
     'title'=>$t('ການສຶກສາ ສາສະໜາ','Religious Education','宗教教育'),
     'desc' =>$t('ຮຽນ ສີລ ສະມາທິ ທັມ ທັງໃນ ແລະ ຕ່າງປະເທດ','Study Sila, Samadhi & Dhamma','學習戒定慧')],
    ['num'=>'02','icon'=>'fas fa-chalkboard-teacher',
     'title'=>$t('ດ້ານການສອນ','Teaching','教學工作'),
     'desc' =>$t('ຝຶກອົບຮົມ ແລະ ພັດທະນາຄູສອນ','Teacher training & development','培訓教師')],
    ['num'=>'03','icon'=>'fas fa-microscope',
     'title'=>$t('ການຄົ້ນຄ້ວາ ວິໄຈ','Research','學術研究'),
     'desc' =>$t('ທັດທາ ແລະ ວິໄຊ ດ້ານ ສາສະໜາ ລາວ','Buddhist research in Laos','佛教學術研究')],
    ['num'=>'04','icon'=>'fas fa-hands-helping',
     'title'=>$t('ສາສາ ແລະ ສັງຄົມ','Society & Religion','宗教與社會'),
     'desc' =>$t('ກິດຈະກຳ ສຶກສາ ສາດ ໃນ ຊຸມຊົນ','Community religious activities','社區宗教活動')],
    ['num'=>'05','icon'=>'fas fa-globe-asia',
     'title'=>$t('ການທູດສາສາສາ','Religious Diplomacy','宗教外交'),
     'desc' =>$t('ຄວາມສໍາພັນ ກັບ ອົງການ ສາກົນ','International Buddhist relations','國際佛教關係')],
    ['num'=>'06','icon'=>'fas fa-exchange-alt',
     'title'=>$t('ແລກປ່ຽນ ສາກົນ',"Int'l Exchange",'國際交流'),
     'desc' =>$t('ໂຄງການ ແລກປ່ຽນ ພ້ອຍ ແລະ ສາມະເນນ','Monk exchange programs','僧侶交流項目')],
    ['num'=>'07','icon'=>'fas fa-file-signature',
     'title'=>$t('MOU ຕ່າງປະເທດ',"Int'l MOU",'國際協議'),
     'desc' =>$t('ບົດບັນທຶກ ຄວາມເຂົ້າໃຈ ກັບ ປະເທດ ຕ່າງໆ','MOU with foreign countries','與外國簽署協議')],
    ['num'=>'08','icon'=>'fas fa-hand-holding-heart',
     'title'=>$t('ໂຄງການ ຊ່ວຍເຫຼືອ','Aid Projects','援助項目'),
     'desc' =>$t('ໂຄງການ ສາດ ຊ່ວຍ ສັງຄົມ ແລະ ຊຸດຊົນ','Community aid programs','社會援助計劃')],
  ];
@endphp

<section class="py-14 bg-surface-container-lowest">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Section header --}}
    <div class="text-center mb-8">
      <span class="inline-block text-xs font-bold text-primary uppercase tracking-widest
                   mb-3 bg-primary/5 px-4 py-1.5 rounded-full border border-primary/10">
        {{ $t('ພາລະກິດ','Our Mission','使命') }}
      </span>
      <h2 class="text-3xl font-serif font-extrabold text-primary mb-3">
        {{ $t('ວຽກງານ ແລະ ພາລະກິດ','Work Areas & Mission','工作範圍與使命') }}
      </h2>
      <p class="text-on-surface-variant text-sm max-w-lg mx-auto leading-relaxed">
        {{ $t(
          'ຂົງເຂດ ການ ເຮັດວຽກ ຂອງ ອພສ ທັງ ດ້ານ ສາສະໜາ ແລະ ສາກົນ',
          'Key areas of work of BFOL in religious and international fields',
          'BFOL的工作範疇，涵蓋宗教與國際領域'
        ) }}
      </p>
      <div class="h-1 w-16 bg-gradient-to-r from-secondary to-secondary-container mt-4 mx-auto rounded-full"></div>
    </div>

    {{-- Card grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach($areas as $a)
        <div class="group relative flex flex-col items-center text-center cursor-default
                     p-5 md:p-6 rounded-lg bg-surface border border-outline-variant/30 overflow-hidden
                     shadow-sm hover:shadow-lg hover:-translate-y-1.5 hover:border-secondary/30
                     transition-all duration-300">

          {{-- Bottom accent line --}}
          <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-secondary to-secondary-container
                       scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>

          <span class="absolute top-3 right-3 text-[10px] font-bold font-sans
                        text-outline/30 group-hover:text-outline transition-colors select-none">
            {{ $a['num'] }}
          </span>

          <div class="w-14 h-14 rounded-md bg-primary/5 flex items-center justify-center mb-4
                       group-hover:scale-110 transition-all duration-300">
            <i class="{{ $a['icon'] }} text-primary text-xl"></i>
          </div>

          <h3 class="font-serif font-bold text-on-surface text-sm mb-1.5
                      group-hover:text-primary transition-colors leading-[1.5]">
            {{ $a['title'] }}
          </h3>

          <p class="text-xs text-on-surface-variant leading-relaxed">
            {{ $a['desc'] }}
          </p>

          <div class="mt-4 flex items-center gap-1 text-xs font-semibold text-secondary
                       opacity-0 group-hover:opacity-100 translate-y-1.5 group-hover:translate-y-0
                       transition-all duration-200">
            {{ $t('ເບິ່ງເພີ່ມ','Learn more','了解更多') }}
            <i class="fas fa-arrow-right text-[9px] group-hover:translate-x-0.5 transition-transform"></i>
          </div>
        </div>
      @endforeach
    </div>

  </div>
</section>
