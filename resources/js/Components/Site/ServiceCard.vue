<template>
  <!-- The photo is the whole card. Only a short label and the title sit on it, in a fixed-height block,
       so every title starts at the same height and busy job photos still read as one set. -->
  <Link :href="`/service/${service.slug}`" class="group relative block rounded-xl overflow-hidden s-surface-2 aspect-[4/5] shadow-[var(--s-shadow-sm)] hover:shadow-[var(--s-shadow-lg)] transition-shadow">
    <img :src="service.image ? img(service.image, 640) : '/logo.png'" :srcset="srcset(service.image, 1024)" sizes="(min-width: 1280px) 290px, (min-width: 1024px) 33vw, 50vw" :alt="service.name" loading="lazy" decoding="async" width="640" height="800"
      class="absolute inset-0 img-cover object-[50%_40%] group-hover:scale-[1.05] transition-transform duration-700" />
    <div class="absolute inset-0" style="background: linear-gradient(180deg, rgba(11, 31, 51, 0) 45%, rgba(11, 31, 51, 0.7) 72%, rgba(11, 31, 51, 0.95) 100%)"></div>

    <span class="absolute top-3 right-3 w-9 h-9 rounded-full bg-white/15 backdrop-blur text-white grid place-items-center transition group-hover:bg-[var(--s-accent)]" aria-hidden="true">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M9 7h8v8" /></svg>
    </span>

    <div class="absolute inset-x-0 bottom-0 p-4 sm:p-5 text-white">
      <h3 class="text-[16px] sm:text-[18px] font-bold leading-snug line-clamp-2 min-h-[2lh]" style="font-family: var(--font-display)">{{ service.name }}</h3>
      <span class="mt-2 flex items-center justify-between gap-2 text-[13px] font-semibold text-white/85">
        <span v-if="service.from_price">From S${{ Number(service.from_price).toLocaleString() }}</span>
        <span v-else-if="service.response_time" class="truncate">{{ service.response_time }}</span>
        <span v-else>View service</span>
        <span class="shrink-0 transition-transform group-hover:translate-x-0.5" aria-hidden="true">→</span>
      </span>
    </div>
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { img, srcset } from '@/utils/img';

defineProps({ service: { type: Object, required: true } });
</script>
