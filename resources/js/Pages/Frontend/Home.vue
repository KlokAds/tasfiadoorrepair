<template>
  <FrontendLayout>
    <!-- ============ Hero: message left, framed work photo right (photo on top on phones) ============ -->
    <section class="relative s-bg-alt border-b s-border overflow-hidden">
      <!-- Phones and tablets: the same framed photo as on desktop, above the text -->
      <div v-if="heroPhoto" class="lg:hidden container-app pt-6 sm:pt-8">
        <div class="relative mr-3 mb-3">
          <div class="absolute -right-3 -bottom-3 w-full h-full rounded-2xl bg-[var(--s-accent)] opacity-90" aria-hidden="true"></div>
          <div class="relative aspect-[4/3] sm:aspect-[16/10] rounded-2xl overflow-hidden s-surface-2 shadow-[var(--s-shadow-lg)]">
            <img :src="img(heroPhoto, 1024)" :srcset="srcset(heroPhoto, 1600)" :sizes="heroSizes" :alt="hero.title || company.name"
              width="1024" height="768" class="img-cover object-[45%_20%]" fetchpriority="high" decoding="async" />
            <p class="absolute right-3 top-3 inline-flex items-center gap-2 rounded-md bg-black/55 backdrop-blur px-3 py-1.5 text-[13px] font-semibold text-white">
              <span class="w-1.5 h-1.5 rounded-full bg-[#4ade80]"></span>{{ heroCaption }}
            </p>
          </div>
          <!-- Close-up of the work, overlapping the bottom-left corner -->
          <div class="absolute left-3 -bottom-5 w-28 aspect-[4/3] rounded-lg overflow-hidden border-[3px] border-[var(--s-bg-alt)] shadow-[var(--s-shadow-lg)] s-surface-2">
            <img :src="img(heroDetail, 320)" :srcset="srcset(heroDetail, 640)" sizes="112px" alt="Close-up: fitting a floor spring under a glass door" loading="lazy" decoding="async" class="img-cover" />
          </div>
        </div>
      </div>

      <div class="container-app grid gap-12 xl:gap-20 lg:grid-cols-[1.1fr_0.9fr] items-center pt-10 pb-8 sm:pt-12 sm:pb-12 lg:py-20">
        <div class="max-w-xl">
          <p v-if="hero.eyebrow" class="eyebrow">{{ hero.eyebrow }}</p>
          <h1 class="h-display mt-4 lg:mt-5">{{ hero.title || `Door repair services in Singapore` }}</h1>
          <p v-if="hero.subtitle" class="mt-5 lg:mt-6 lead">{{ hero.subtitle }}</p>

          <div class="mt-8 lg:mt-9 flex flex-col sm:flex-row gap-3">
            <WhatsAppButton size="lg">Get a free quote</WhatsAppButton>
            <component v-if="hero.btn_link && hero.btn_link !== '/contact'" :is="isInternal(hero.btn_link) ? Link : 'a'" :href="hero.btn_link" class="btn btn-secondary btn-lg">
              {{ hero.btn_name || 'Our services' }}
            </component>
            <a v-else-if="company.tel" :href="'tel:' + tel" class="btn btn-secondary btn-lg">Call {{ company.phone }}</a>
          </div>

          <ul v-if="hero.badges?.length" class="mt-8 flex flex-wrap gap-x-6 gap-y-3">
            <li v-for="b in hero.badges" :key="b" class="flex items-center gap-2 text-[15px] font-semibold s-text">
              <svg class="w-5 h-5 s-accent shrink-0" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
              {{ b }}
            </li>
          </ul>
          <div v-if="reviews.google" class="mt-6 inline-flex items-center gap-3 text-[15px] s-muted">
            <Stars :value="reviews.google.rating" />
            <span><strong class="s-heading">{{ reviews.google.rating?.toFixed(1) }}</strong> from {{ reviews.google.total }} Google reviews</span>
          </div>
        </div>

        <!-- Desktop: the tall photo in a tall frame, with a close-up photo and a rating card layered on it -->
        <div v-if="heroPhoto" class="hidden lg:block relative justify-self-end w-full max-w-[30rem] mr-6 mb-6 mt-4">
          <div class="absolute -right-5 -bottom-5 w-full h-full rounded-2xl bg-[var(--s-accent)] opacity-90" aria-hidden="true"></div>
          <div class="relative aspect-[4/5] rounded-2xl overflow-hidden s-surface-2 shadow-[var(--s-shadow-lg)]">
            <img :src="img(heroPhoto, 1024)" :srcset="srcset(heroPhoto, 1600)" :sizes="heroSizes" :alt="hero.title || company.name"
              width="800" height="1000" class="img-cover object-[45%_30%]" fetchpriority="high" decoding="async" />
            <p class="absolute right-4 bottom-4 inline-flex items-center gap-2 rounded-md bg-black/55 backdrop-blur px-3 py-1.5 text-[13px] font-semibold text-white">
              <span class="w-1.5 h-1.5 rounded-full bg-[#4ade80]"></span>{{ heroCaption }}
            </p>
          </div>

          <!-- Close-up of the work, overlapping the top-left corner -->
          <div class="absolute -left-12 top-10 w-44 aspect-[4/3] rounded-xl overflow-hidden border-4 border-[var(--s-bg-alt)] shadow-[var(--s-shadow-lg)] s-surface-2">
            <img :src="img(heroDetail, 480)" :srcset="srcset(heroDetail, 800)" sizes="176px" alt="Close-up: fitting a floor spring under a glass door" class="img-cover" loading="lazy" decoding="async" />
          </div>

          <!-- Rating card, overlapping the bottom-left corner -->
          <div class="absolute -left-12 bottom-12 card px-5 py-4 flex items-center gap-4" style="box-shadow: var(--s-shadow-lg)">
            <span class="w-12 h-12 rounded-full bg-[var(--s-accent-soft)] s-accent grid place-items-center shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" :d="(stats[1] || stats[0] || {}).icon || 'M14.8 14.8a4 4 0 01-5.6 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'" /></svg>
            </span>
            <div>
              <template v-if="reviews.google">
                <p class="flex items-center gap-2"><Stars :value="reviews.google.rating" /><strong class="s-heading text-[15px]">{{ reviews.google.rating?.toFixed(1) }}</strong></p>
                <p class="text-[13px] s-muted mt-0.5">{{ reviews.google.total }} Google reviews</p>
              </template>
              <template v-else-if="stats[1] || stats[0]">
                <p class="text-[1.4rem] font-extrabold leading-none s-heading">{{ (stats[1] || stats[0]).value }}</p>
                <p class="text-[13px] s-muted mt-1">{{ (stats[1] || stats[0]).label }}</p>
              </template>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ Numbers (navy band) ============ -->
    <section v-if="stats.length" class="band-navy">
      <ul class="container-app grid grid-cols-2 lg:grid-cols-4">
        <li v-for="(c, i) in stats" :key="c.id" :class="['py-9 px-3 sm:px-6 flex items-center gap-4', i % 2 ? 'border-l border-white/10' : '', i > 1 ? 'border-t lg:border-t-0 border-white/10' : '', i === 2 ? 'lg:border-l' : '', i === 0 ? 'lg:pl-0' : '']">
          <span class="hidden sm:grid w-12 h-12 rounded-lg bg-white/10 text-[#9ec9f5] place-items-center shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" :d="c.icon" /></svg></span>
          <div>
            <p class="text-[2.1rem] font-extrabold text-white leading-none tabular-nums tracking-tight">{{ c.value }}</p>
            <p class="mt-2 text-[15px] text-white/70">{{ c.label }}</p>
          </div>
        </li>
      </ul>
    </section>

    <!-- Everything below the first screen is built one frame later (splits the start-up work in two). -->
    <template v-if="restReady">
    <!-- ============ Services ============ -->
    <section v-if="services.length" class="section-y s-bg">
      <div class="container-app">
        <div class="grid lg:grid-cols-[1fr_1fr] gap-6 items-end mb-10">
          <div>
            <p class="eyebrow">Our services</p>
            <h2 class="h-section mt-3">{{ homeStatic?.h_s_title || 'Door repairs for Singapore homes and businesses' }}</h2>
          </div>
          <p class="lead">{{ homeStatic?.h_s_subtitle || 'Sliding, glass, wooden and wardrobe doors, hinges, locks and floor springs, with the price agreed before we start.' }}</p>
        </div>

        <div v-if="categories.length" class="flex flex-wrap gap-2 mb-6">
          <Link v-for="c in categories.filter(c => c.services_count)" :key="c.id" :href="`/services/${c.slug}`" class="chip">{{ c.name }} <span class="s-subtle">{{ c.services_count }}</span></Link>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-5">
          <ServiceCard v-for="s in services" :key="s.id" :service="s" />
        </div>
        <div class="mt-10 text-center">
          <Link href="/services" class="btn btn-secondary">View all {{ allServicesCount }} services</Link>
        </div>
      </div>
    </section>

    <!-- ============ Before & after (real jobs, interactive) ============ -->
    <section v-if="showcase.length" class="section-y s-bg-alt border-y s-border">
      <div class="container-app">
        <div class="grid lg:grid-cols-[1fr_1fr] gap-6 items-end mb-10">
          <div>
            <p class="eyebrow">Before &amp; after</p>
            <h2 class="h-section mt-3">See the difference on real jobs</h2>
          </div>
          <p class="lead lg:pb-1">Drag the handle on the photo. These are doors our technicians repaired in Singapore homes and offices.</p>
        </div>
        <!-- Portrait frame (most job photos are portrait), so nothing is cropped away and the section stays compact -->
        <div class="grid lg:grid-cols-[minmax(0,26rem)_1fr] gap-8 lg:gap-12 items-center">
          <BeforeAfter :key="active.id" :before="active.bef_img" :after="active.aft_img" :title="active.name" ratio="aspect-[4/5]" class="w-full max-w-md mx-auto lg:max-w-none" />
          <div class="min-w-0">
            <div class="grid gap-3" role="tablist" aria-label="Choose a job">
              <button v-for="(j, i) in showcase" :key="j.id" type="button" role="tab" :aria-selected="i === activeJob" @click="activeJob = i"
                :class="['text-left rounded-lg border p-3 sm:p-4 flex items-center gap-4 transition', i === activeJob ? 'border-[var(--s-accent)] bg-[var(--s-surface)] shadow-[var(--s-shadow)]' : 's-border hover:border-[var(--s-border-2)] hover:bg-[var(--s-surface)]']">
                <img :src="img(j.aft_img, 160)" alt="" width="72" height="72" loading="lazy" decoding="async" class="w-16 h-16 sm:w-[72px] sm:h-[72px] rounded-md object-cover shrink-0 border s-border" />
                <span class="min-w-0">
                  <span class="block font-bold s-heading text-[16px] leading-snug">{{ j.name }}</span>
                  <span v-if="j.short_summary" class="mt-1 block text-[14.5px] s-muted leading-relaxed line-clamp-2">{{ j.short_summary }}</span>
                </span>
              </button>
            </div>
            <Link :href="`/service/${active.slug}`" class="mt-6 inline-flex link items-center gap-1.5">About {{ active.name.toLowerCase() }} <span aria-hidden="true">→</span></Link>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ Reviews (navy) ============ -->
    <section v-if="reviews.items.length" class="section-y band-navy">
      <div class="container-app">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
          <div class="max-w-2xl">
            <p class="eyebrow eyebrow-light">Customer reviews</p>
            <h2 class="h-section !text-white mt-3">{{ homeStatic?.h_test_title || 'What our customers say' }}</h2>
            <p v-if="reviews.google" class="mt-3 text-[1.1rem] text-white/75">Rated <strong class="text-white">{{ reviews.google.rating?.toFixed(1) }} out of 5</strong> from {{ reviews.google.total }} Google reviews.</p>
          </div>
          <Link href="/reviews" class="btn btn-light shrink-0">Read all reviews</Link>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
          <ReviewCard v-for="(r, i) in reviews.items" :key="i" :review="r" />
        </div>
      </div>
    </section>

    <!-- ============ Quote (own section when switched on in admin) ============ -->
    <section v-if="hero.show_quote_form" id="quote" class="section-y s-bg-alt border-y s-border">
      <div class="container-app grid grid-cols-1 lg:grid-cols-[1fr_1.1fr] gap-10 lg:gap-16 items-center">
        <div class="min-w-0">
          <p class="eyebrow">Free quote</p>
          <h2 class="h-section mt-3">{{ texts.steps_title || 'Tell us about the door. We reply with a price.' }}</h2>
          <p v-if="texts.quote_intro" class="lead mt-3">{{ texts.quote_intro }}</p>
          <ol v-if="steps.length" class="mt-8 s-divide border-y s-border">
            <li v-for="(step, i) in steps" :key="step.title" class="flex gap-4 py-5">
              <span class="w-9 h-9 rounded-md grid place-items-center text-[16px] font-extrabold shrink-0 bg-[var(--s-accent-soft)] s-accent" style="font-family: var(--font-display)">{{ i + 1 }}</span>
              <div>
                <p class="font-bold s-heading" style="font-family: var(--font-display)">{{ step.title }}</p>
                <p class="mt-1 text-[15.5px] s-muted leading-relaxed">{{ step.text }}</p>
              </div>
            </li>
          </ol>
        </div>
        <div class="card min-w-0 p-5 sm:p-8" style="box-shadow: var(--s-shadow-lg)">
          <h3 class="h-card !text-[1.2rem]">Request a free quote</h3>
          <p class="text-[15px] s-muted mt-1 mb-6">We usually reply within a few hours.</p>
          <QuoteForm :services="serviceOptions" subject="Quote request · Homepage" id-prefix="hero" />
        </div>
      </div>
    </section>

    <!-- ============ How it works (when the quote form is off) ============ -->
    <section v-else-if="steps.length" class="section-y s-bg-alt border-y s-border">
      <div class="container-app">
        <div class="max-w-2xl">
          <p class="eyebrow">How it works</p>
          <h2 class="h-section mt-3">{{ texts.steps_title }}</h2>
        </div>
        <ol class="mt-10 grid gap-5 md:grid-cols-3">
          <li v-for="(step, i) in steps" :key="step.title" class="card p-7">
            <span class="w-10 h-10 rounded-md grid place-items-center text-[16px] font-extrabold bg-[var(--s-accent-soft)] s-accent" style="font-family: var(--font-display)">{{ i + 1 }}</span>
            <h3 class="h-card mt-4">{{ step.title }}</h3>
            <p class="mt-2 text-[15.5px] s-muted leading-relaxed">{{ step.text }}</p>
          </li>
        </ol>
      </div>
    </section>

    <!-- ============ Partners ============ -->
    <section v-if="partners.length >= 3" class="py-10 s-bg border-b s-border">
      <div class="container-app flex flex-col md:flex-row items-center gap-6 md:gap-12">
        <p class="footer-h !mb-0 shrink-0">Trusted by</p>
        <div class="flex flex-wrap items-center justify-center md:justify-start gap-x-12 gap-y-6">
          <div v-for="p in partners" :key="p.id" class="h-10 w-28 flex items-center justify-center opacity-60 hover:opacity-100 transition dark:invert dark:hue-rotate-180">
            <img :src="'/' + p.image" alt="" class="max-h-full max-w-full object-contain grayscale" loading="lazy" />
          </div>
        </div>
      </div>
    </section>

    <!-- ============ Areas ============ -->
    <section v-if="topLocations.length" class="section-y s-bg">
      <div class="container-app grid lg:grid-cols-[1fr_1.4fr] gap-10 items-start">
        <div>
          <p class="eyebrow">Areas we serve</p>
          <h2 class="h-section mt-3">{{ texts.areas_title }}</h2>
          <p v-if="texts.areas_lead" class="lead mt-3">{{ texts.areas_lead }}</p>
          <Link href="/locations" class="btn btn-secondary mt-6">All areas</Link>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 border-t s-border">
          <Link v-for="l in topLocations" :key="l.href" :href="l.href" class="flex items-center justify-between gap-2 py-3 border-b s-border text-[15.5px] s-text hover:text-[var(--s-accent-text)]">
            <span class="truncate">{{ l.name }}</span><span class="s-subtle" aria-hidden="true">→</span>
          </Link>
        </div>
      </div>
    </section>

    <!-- ============ Articles ============ -->
    <section v-if="latestBlogs.length" class="section-y s-bg border-t s-border">
      <div class="container-app">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
          <div class="max-w-2xl">
            <p class="eyebrow">Guides &amp; advice</p>
            <h2 class="h-section mt-3">{{ homeStatic?.h_b_title || 'Know the price before you call' }}</h2>
          </div>
          <Link href="/blogs" class="btn btn-secondary shrink-0">All articles</Link>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
          <ArticleCard v-for="b in latestBlogs" :key="b.id" :article="b" />
        </div>
      </div>
    </section>
    </template>
  </FrontendLayout>
</template>

<script setup>
import { computed, nextTick, onMounted, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import ServiceCard from '@/Components/Site/ServiceCard.vue';
import ReviewCard from '@/Components/Site/ReviewCard.vue';
import ArticleCard from '@/Components/Site/ArticleCard.vue';
import QuoteForm from '@/Components/Site/QuoteForm.vue';
import Stars from '@/Components/Site/Stars.vue';
import BeforeAfter from '@/Components/Site/BeforeAfter.vue';
import WhatsAppButton from '@/Components/Site/WhatsAppButton.vue';
import { img, srcset } from '@/utils/img';
import { statsFrom } from '@/utils/stats';

const restReady = ref(false);
onMounted(() => {
  requestAnimationFrame(() => setTimeout(async () => {
    restReady.value = true;
    // A link such as /#quote points into the part that was just built.
    if (location.hash.length > 1) {
      await nextTick();
      document.getElementById(decodeURIComponent(location.hash.slice(1)))?.scrollIntoView();
    }
  }));
});

const props = defineProps({
  hero: { type: Object, default: () => ({}) },
  homeStatic: Object,
  services: { type: Array, default: () => [] },
  allServicesCount: Number,
  serviceOptions: { type: Array, default: () => [] },
  categories: { type: Array, default: () => [] },
  counters: { type: Array, default: () => [] },
  reviews: { type: Object, default: () => ({ items: [], google: null }) },
  latestBlogs: { type: Array, default: () => [] },
  partners: { type: Array, default: () => [] },
  showcase: { type: Array, default: () => [] },
});

const stats = computed(() => statsFrom(props.counters));

// Hero photo: the one set in admin, else the first service photo.
const heroPhoto = computed(() => props.hero.image || props.services.find((s) => s.image)?.image || null);
// Same sizes on both hero images, so the browser downloads the photo only once.
const heroSizes = '(min-width: 1024px) 480px, 100vw';
// Small close-up photo layered on the desktop hero (from the photo library).
const heroDetail = 'Images/fl1.jpg';
const heroCaption = computed(() => texts.value.hero_caption || 'Sliding glass door repair, Singapore');

// Before & after: the job shown in the big slider.
const activeJob = ref(0);
const active = computed(() => props.showcase[activeJob.value] || props.showcase[0] || {});

const page = usePage();
const company = computed(() => page.props.company || {});
const tel = computed(() => company.value.tel || '');
const topLocations = computed(() => page.props.topLocations || []);
const isInternal = href => !href || href.startsWith('/');

const texts = computed(() => page.props.company?.texts || {});
// Admin → Website text → Homepage: How it works (a step without a title is hidden).
const steps = computed(() => [1, 2, 3].map((n) => ({ title: texts.value[`step${n}_title`], text: texts.value[`step${n}_text`] })).filter((s) => s.title));
</script>
