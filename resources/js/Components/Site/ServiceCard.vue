<template>
  <!-- Clean photo on top, the service in plain words below: readable at a glance and every card lines up. -->
  <Link :href="`/service/${service.slug}`" class="group card card-link overflow-hidden flex flex-col h-full">
    <div class="relative aspect-[4/3] overflow-hidden s-surface-2">
      <img :src="service.image ? img(service.image, 640) : '/logo.png'" :srcset="srcset(service.image, 1024)" sizes="(min-width: 1280px) 290px, (min-width: 1024px) 33vw, 50vw" :alt="service.name" loading="lazy" decoding="async" width="640" height="480"
        class="absolute inset-0 img-cover object-[50%_40%] group-hover:scale-[1.04] transition-transform duration-700" />
    </div>

    <div class="flex flex-col flex-1 p-4 sm:p-5">
      <!-- Titles keep two lines of room on wider screens, so the summaries below start at the same height. -->
      <h3 class="text-[16px] sm:text-[18px] font-bold leading-snug s-heading line-clamp-3 sm:line-clamp-2 sm:min-h-[2lh] group-hover:text-[var(--s-accent)] transition-colors" style="font-family: var(--font-display)">{{ service.name }}</h3>
      <p v-if="service.short_summary" class="hidden sm:block mt-2 text-[14.5px] s-muted leading-relaxed"><span class="line-clamp-2">{{ service.short_summary }}</span></p>
      <span class="mt-auto pt-4 flex items-center justify-between gap-2 text-[14px] font-semibold">
        <span v-if="service.from_price" class="s-heading">From S${{ groupDigits(service.from_price) }}</span>
        <span v-else class="s-muted">Free quote</span>
        <span class="s-accent inline-flex items-center gap-1">
          <span class="hidden sm:inline">Details</span>
          <span class="transition-transform group-hover:translate-x-0.5" aria-hidden="true">→</span>
        </span>
      </span>
    </div>
  </Link>
</template>

<script setup>
import { groupDigits } from '@/utils/fmt';
import { Link } from '@inertiajs/vue3';
import { img, srcset } from '@/utils/img';

defineProps({ service: { type: Object, required: true } });
</script>
