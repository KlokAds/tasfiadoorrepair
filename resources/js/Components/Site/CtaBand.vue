<template>
  <section class="section-y s-bg">
    <div class="container-app">
      <div class="relative overflow-hidden rounded-xl px-6 py-10 sm:px-12 sm:py-14 text-white" style="background: linear-gradient(120deg, #0b63b0 0%, #0a4f8f 100%)">
        <div class="absolute -right-24 -top-24 w-80 h-80 rounded-full bg-white/10"></div>
        <div class="absolute right-24 -bottom-32 w-64 h-64 rounded-full bg-white/5"></div>
        <div class="relative grid lg:grid-cols-[1fr_auto] gap-8 items-center">
          <div class="max-w-2xl">
            <h2 class="h-section !text-white">{{ heading }}</h2>
            <p v-if="body" class="mt-3 text-white/85 text-[1.05rem] leading-relaxed">{{ body }}</p>
          </div>
          <div class="flex flex-col sm:flex-row gap-3">
            <WhatsAppButton size="lg" :topic="topic" class="!bg-white !text-[#0b3f6e] hover:!bg-[#eaf2fa]">Get a free quote</WhatsAppButton>
            <a v-if="company.tel" :href="'tel:' + company.tel" class="btn btn-light btn-lg">Call {{ company.phone }}</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import WhatsAppButton from '@/Components/Site/WhatsAppButton.vue';

// Texts come from Admin → Website text. "page" picks that page's banner (services, projects,
// locations); empty fields fall back to the general banner.
const props = defineProps({
  page: { type: String, default: '' },
  title: String,
  text: String,
  topic: { type: String, default: '' },
});
const company = computed(() => usePage().props.company || {});
const t = computed(() => company.value.texts || {});
const heading = computed(() => props.title || (props.page && t.value[`cta_${props.page}_title`]) || t.value.cta_title);
const body = computed(() => props.text || (props.page && t.value[`cta_${props.page}_text`]) || t.value.cta_text);
</script>
