<template>
  <div class="admin-ui min-h-screen grid lg:grid-cols-[1.05fr_1fr]">
    <!-- Brand side -->
    <aside class="hidden lg:flex relative overflow-hidden flex-col justify-between p-12 text-white" style="background: #0b1f33">
      <div class="relative">
        <span class="inline-flex rounded-md bg-white p-2"><img src="/logo.png" alt="Tasfia Door Repair Singapore" class="h-16 w-auto object-contain" /></span>
      </div>

      <div class="relative max-w-md">
        <p class="text-[12px] font-bold uppercase tracking-[0.18em] text-[#8cc8f5]">Tasfia Door Repair · Admin</p>
        <h1 class="mt-3 text-4xl font-extrabold tracking-tight leading-tight" style="font-family: var(--font-display)">Every door job, every page, one dashboard.</h1>
        <p class="mt-4 text-[16px] text-white/65 leading-relaxed">Service pages, prices, projects, reviews, articles and customer enquiries, with SEO checks on everything you publish.</p>
        <ul class="mt-8 space-y-3 text-sm text-white/80">
          <li class="flex items-center gap-3"><span class="w-6 h-6 rounded bg-white/10 flex items-center justify-center text-[#8cc8f5]">✓</span>Autosave, so nothing is lost</li>
          <li class="flex items-center gap-3"><span class="w-6 h-6 rounded bg-white/10 flex items-center justify-center text-[#8cc8f5]">✓</span>Approval before anything goes live</li>
          <li class="flex items-center gap-3"><span class="w-6 h-6 rounded bg-white/10 flex items-center justify-center text-[#8cc8f5]">✓</span>SEO, AI search and trust score on every page</li>
        </ul>
      </div>

      <p class="relative text-xs text-white/40">© {{ new Date().getFullYear() }} Tasfia Door Repair · Singapore</p>
    </aside>

    <!-- Form side -->
    <main class="flex flex-col justify-center px-6 py-12 sm:px-12">
      <div class="w-full max-w-sm mx-auto">
        <div class="lg:hidden flex items-center gap-3 mb-10">
          <img src="/logo.png" alt="" class="h-12 w-auto rounded-md bg-white p-1 border a-border" />
          <span class="font-bold" style="font-family: var(--font-display)">Tasfia Door Repair</span>
        </div>

        <slot />

        <div class="mt-10 flex items-center justify-between text-xs a-subtle">
          <Link href="/" class="a-hover-text">← Back to website</Link>
          <div class="a-seg">
            <button v-for="t in ['light', 'dark']" :key="t" type="button" @click="setTheme(t)" :class="['capitalize', theme === t && 'is-on']">{{ t }}</button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const theme = ref('light');

function setTheme(t) {
  theme.value = t;
  document.documentElement.classList.toggle('dark', t === 'dark');
  try { localStorage.setItem('tasfia_admin_theme', t); } catch (e) { /* ignore */ }
}

onMounted(() => {
  theme.value = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
});
</script>
