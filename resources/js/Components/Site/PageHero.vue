<template>
  <section class="relative s-bg-alt border-b s-border overflow-hidden">
    <!-- Banner photo from Admin > Page banners: a soft texture on the right, faded into the background -->
    <div v-if="image" class="absolute inset-y-0 right-0 w-full lg:w-3/5 pointer-events-none" aria-hidden="true">
      <img :src="img(image, 1280)" alt="" class="img-cover opacity-25 dark:opacity-20" loading="lazy" decoding="async"
        style="mask-image: linear-gradient(90deg, transparent, #000 60%); -webkit-mask-image: linear-gradient(90deg, transparent, #000 60%)" />
    </div>
    <div :class="['relative container-app', compact ? 'py-9 sm:py-11' : 'py-11 sm:py-14']">
      <nav v-if="crumbs.length" class="crumbs mb-4" aria-label="Breadcrumb">
        <Link href="/" class="hover:!text-[var(--s-accent-text)]">Home</Link>
        <template v-for="c in crumbs" :key="c.label">
          <span class="sep">/</span>
          <Link v-if="c.href" :href="c.href" class="hover:!text-[var(--s-accent-text)]">{{ c.label }}</Link>
          <span v-else class="s-heading font-semibold" aria-current="page">{{ c.label }}</span>
        </template>
      </nav>
      <div class="grid lg:grid-cols-[1fr_auto] gap-8 items-end">
        <div class="max-w-3xl">
          <p v-if="eyebrow" class="eyebrow">{{ eyebrow }}</p>
          <h1 class="h-page mt-2.5">{{ title }}</h1>
          <p v-if="lead" class="mt-4 lead max-w-2xl">{{ lead }}</p>
          <slot name="below" />
        </div>
        <slot />
        <!-- Pages without their own banner content get a quick help card on the right -->
        <div v-if="help && !$slots.default && !compact && company.tel" class="hidden lg:block card p-5 w-72" style="box-shadow: var(--s-shadow)">
          <p class="font-bold s-heading">Need a door fixed?</p>
          <p class="mt-1 text-[14px] s-muted leading-relaxed">Send a photo. We reply with a price, usually the same day.</p>
          <div class="mt-4 grid grid-cols-2 gap-2">
            <a :href="'tel:' + company.tel" class="btn btn-secondary btn-sm">Call</a>
            <WhatsAppButton size="sm" green>WhatsApp</WhatsAppButton>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import WhatsAppButton from '@/Components/Site/WhatsAppButton.vue';
import { img } from '@/utils/img';

defineProps({
  title: { type: String, required: true },
  eyebrow: String,
  lead: String,
  image: String,
  crumbs: { type: Array, default: () => [] },
  compact: Boolean,
  // Pages that already have their own quote box can switch the help card off.
  help: { type: Boolean, default: true },
});
const company = computed(() => usePage().props.company || {});
</script>
