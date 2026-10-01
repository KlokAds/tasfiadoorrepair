<template>
  <!-- Photo tile like the service cards: the job photo, its name and the service, nothing else -->
  <figure class="group relative rounded-xl overflow-hidden s-surface-2 aspect-[4/5] shadow-[var(--s-shadow-sm)] hover:shadow-[var(--s-shadow-lg)] transition-shadow">
    <img :src="project.image ? img(project.image, 640) : '/logo.png'" :srcset="srcset(project.image, 1024)" sizes="(min-width: 1280px) 290px, (min-width: 1024px) 33vw, 50vw" :alt="project.name" loading="lazy" decoding="async" width="640" height="800"
      class="absolute inset-0 img-cover object-[50%_40%] group-hover:scale-[1.05] transition-transform duration-700" />
    <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(180deg, rgba(11, 31, 51, 0) 45%, rgba(11, 31, 51, 0.6) 72%, rgba(11, 31, 51, 0.92) 100%)"></div>

    <a v-if="project.video" :href="project.video" target="_blank" rel="noopener" :aria-label="`Watch video: ${project.name}`"
      class="absolute top-3 right-3 w-10 h-10 rounded-full bg-white/90 text-[#0b1f33] grid place-items-center shadow-md hover:bg-white">
      <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5.1v13.8a1 1 0 001.5.9l11-6.9a1 1 0 000-1.8l-11-6.9A1 1 0 008 5.1z" /></svg>
    </a>

    <figcaption class="absolute inset-x-0 bottom-0 p-4 sm:p-5 text-white">
      <Link v-if="project.service && showService" :href="`/service/${project.service.slug}`" class="inline-block mb-2 rounded bg-white/15 backdrop-blur px-2 py-0.5 text-[12px] font-semibold hover:bg-white/25">{{ project.service.name }}</Link>
      <p class="text-[15px] sm:text-[17px] font-bold leading-snug line-clamp-2" style="font-family: var(--font-display)">{{ project.name }}</p>
      <p v-if="details" class="mt-1 text-[13px] text-white/75">{{ details }}</p>
    </figcaption>
  </figure>
</template>

<script setup>
import { monthYear } from '@/utils/fmt';
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { img, srcset } from '@/utils/img';

const props = defineProps({ project: { type: Object, required: true }, showService: { type: Boolean, default: true } });
const details = computed(() => [
  props.project.area || props.project.location?.name,
  props.project.property_type,
  props.project.completed_on && monthYear(props.project.completed_on),
].filter(Boolean).join(' · '));
</script>
