<template>
  <Head v-if="meta" :title="meta.title">
    <meta head-key="description" name="description" :content="meta.description" />
    <meta head-key="robots" name="robots" :content="meta.robots" />
    <link head-key="canonical" rel="canonical" :href="meta.canonical" />
    <meta head-key="og:type" property="og:type" :content="meta.type" />
    <meta head-key="og:site_name" property="og:site_name" :content="meta.site_name" />
    <meta head-key="og:title" property="og:title" :content="meta.title" />
    <meta head-key="og:description" property="og:description" :content="meta.description" />
    <meta head-key="og:url" property="og:url" :content="meta.canonical" />
    <meta head-key="og:image" property="og:image" :content="meta.image" />
    <meta head-key="twitter:card" name="twitter:card" content="summary_large_image" />
  </Head>

  <div class="site min-h-screen flex flex-col pb-[4.25rem] lg:pb-0">
    <div ref="topMarker" aria-hidden="true" class="absolute top-0 left-0 w-px h-2 pointer-events-none"></div>
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[100] btn btn-primary">Skip to content</a>

    <!-- Header -->
    <header :class="['sticky top-0 z-50 border-b s-border transition-shadow', scrolled ? 'shadow-[var(--s-shadow)]' : '']" style="background: var(--s-surface)">
      <div class="container-app h-[4.5rem] lg:h-[4.75rem] flex items-center gap-4 lg:gap-8">
        <Link href="/" class="shrink-0" :aria-label="company.name + ' home'">
          <img :src="img(company.logo, 240)" :alt="company.name" width="104" height="48" class="h-11 lg:h-12 w-auto object-contain rounded bg-white" />
        </Link>

        <!-- Desktop nav -->
        <nav class="hidden xl:flex items-center gap-0.5 h-full" aria-label="Main">
          <Link v-for="item in primaryBefore" :key="item.href" :href="item.href" :class="navClass(item.href)">{{ item.label }}</Link>
          <button type="button" :class="[navClass('/services'), 'gap-1']" :aria-expanded="open === 'services'" aria-haspopup="true"
            @mouseenter="openMenu('services')" @mouseleave="closeMenuSoon" @click="toggle('services')">
            Services
            <svg :class="['w-3.5 h-3.5 opacity-60 transition-transform', open === 'services' && 'rotate-180']" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
          </button>
          <button v-if="showAreasMenu" type="button" :class="[navClass('/locations'), 'gap-1']" :aria-expanded="open === 'locations'" aria-haspopup="true"
            @mouseenter="openMenu('locations')" @mouseleave="closeMenuSoon" @click="toggle('locations')">
            Areas
            <svg :class="['w-3.5 h-3.5 opacity-60 transition-transform', open === 'locations' && 'rotate-180']" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
          </button>
          <Link v-for="item in primaryAfter" :key="item.href" :href="item.href" :class="navClass(item.href)">{{ item.label }}</Link>
        </nav>

        <div class="ml-auto flex items-center gap-2 lg:gap-3">
          <button type="button" @click="searchOpen = true" class="w-10 h-10 rounded-md border s-border grid place-items-center s-muted hover:text-[var(--s-accent-text)] hover:border-[var(--s-accent)]" aria-label="Search the website" title="Search (Ctrl K)">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M21 21l-5.2-5.2M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" /></svg>
          </button>
          <a v-if="company.tel" :href="'tel:' + tel" class="hidden md:flex items-center gap-2.5" :aria-label="`Call us on ${company.phone}`" :title="company.phone">
            <span class="w-10 h-10 rounded-md bg-[var(--s-accent-soft)] s-accent grid place-items-center">
              <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" :d="icons.phone" /></svg>
            </span>
            <span class="leading-tight hidden 2xl:block">
              <span class="block text-[12.5px] s-subtle">Call us</span>
              <span class="block text-[16px] font-bold s-heading whitespace-nowrap" style="font-family: var(--font-display)">{{ company.phone }}</span>
            </span>
          </a>
          <WhatsAppButton size="sm" class="hidden sm:inline-flex !h-10 !px-4">Get a quote</WhatsAppButton>
          <button type="button" @click="mobileOpen = !mobileOpen" class="xl:hidden w-10 h-10 rounded-md border s-border grid place-items-center s-heading" :aria-expanded="mobileOpen" aria-label="Menu">
            <svg v-if="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" /></svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" /></svg>
          </button>
        </div>
      </div>

      <!-- Services panel -->
      <transition enter-active-class="transition duration-150" enter-from-class="opacity-0" leave-active-class="transition duration-100" leave-to-class="opacity-0">
        <div v-if="open === 'services'" class="hidden xl:block absolute inset-x-0 top-full border-t s-border" style="background: var(--s-surface); box-shadow: var(--s-shadow-lg)" @mouseenter="openMenu('services')" @mouseleave="closeMenuSoon">
          <div class="container-app py-8 grid grid-cols-[1fr_18rem] gap-10">
            <div>
              <p class="footer-h">Our door services</p>
              <div v-if="serviceCategories.length > 1" class="grid grid-cols-3 gap-x-8 gap-y-6">
                <div v-for="group in serviceCategories" :key="group.name" class="min-w-0">
                  <Link :href="group.href" class="block pb-2 mb-1 border-b s-border text-[15px] font-bold s-heading hover:text-[var(--s-accent-text)]" @click="open = null">{{ group.name }}</Link>
                  <Link v-for="s in group.services" :key="s.href" :href="s.href" class="block py-1.5 text-[15.5px] leading-snug s-muted hover:text-[var(--s-accent-text)]" @click="open = null">{{ s.name }}</Link>
                </div>
              </div>
              <div v-else class="grid grid-cols-3 gap-x-8">
                <Link v-for="s in serviceCategories[0]?.services || []" :key="s.href" :href="s.href" class="flex items-center justify-between gap-3 py-2.5 border-b s-border text-[15.5px] s-text hover:text-[var(--s-accent-text)]" @click="open = null">
                  <span class="truncate">{{ s.name }}</span>
                  <span class="s-subtle" aria-hidden="true">→</span>
                </Link>
              </div>
            </div>
            <div class="rounded-lg s-bg-alt border s-border p-6 flex flex-col">
              <p class="text-[17px] font-bold leading-snug s-heading" style="font-family: var(--font-display)">Not sure which service you need?</p>
              <p class="mt-2 text-[15px] s-muted leading-relaxed">Send us a photo of the door. We tell you what's wrong and what it costs before we visit.</p>
              <div class="mt-auto pt-5 space-y-2">
                <WhatsAppButton size="sm" class="w-full">Send a photo</WhatsAppButton>
                <Link href="/services" class="btn btn-secondary btn-sm w-full" @click="open = null">All {{ serviceCount }} services</Link>
                <Link href="/pricing" class="block text-center pt-1 text-[14.5px] link" @click="open = null">View price list</Link>
              </div>
            </div>
          </div>
        </div>
      </transition>

      <!-- Areas panel -->
      <transition enter-active-class="transition duration-150" enter-from-class="opacity-0" leave-active-class="transition duration-100" leave-to-class="opacity-0">
        <div v-if="open === 'locations'" class="hidden xl:block absolute inset-x-0 top-full border-t s-border" style="background: var(--s-surface); box-shadow: var(--s-shadow-lg)" @mouseenter="openMenu('locations')" @mouseleave="closeMenuSoon">
          <div class="container-app py-8">
            <p class="footer-h">Areas we serve</p>
            <div class="grid grid-cols-4 gap-x-8">
              <Link v-for="l in topLocations" :key="l.href" :href="l.href" class="flex items-center gap-2 py-2.5 border-b s-border text-[15.5px] s-text hover:text-[var(--s-accent-text)]" @click="open = null">
                <svg class="w-4 h-4 s-subtle" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" :d="icons.pin" /></svg>
                {{ l.name }}
              </Link>
            </div>
            <Link href="/locations" class="inline-block mt-5 link text-[15px]" @click="open = null">Every area we cover →</Link>
          </div>
        </div>
      </transition>

      <!-- Mobile menu -->
      <div v-if="mobileOpen" class="xl:hidden border-t s-border max-h-[calc(100dvh-4.5rem)] overflow-y-auto" style="background: var(--s-surface)">
        <nav class="container-app py-3" aria-label="Mobile">
          <div class="s-divide">
            <Link v-for="item in primaryBefore" :key="item.href" :href="item.href" :class="mobileClass(item.href)" @click="mobileOpen = false">{{ item.label }}</Link>

            <div>
              <button type="button" @click="mobileSection = mobileSection === 'services' ? null : 'services'" class="w-full flex items-center justify-between py-3.5 text-[16px] font-semibold s-heading" :aria-expanded="mobileSection === 'services'">
                Services
                <svg :class="['w-4 h-4 s-subtle transition-transform', mobileSection === 'services' && 'rotate-180']" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
              </button>
              <div v-if="mobileSection === 'services'" class="mb-3 rounded-lg border s-border s-bg-alt">
                <!-- Long lists scroll inside a short box, so the menu stays compact -->
                <div v-if="serviceCount > 8" class="px-3 pt-3">
                  <input v-model="mobileServiceQuery" type="search" class="input !py-2 !text-[15px]" :placeholder="`Find a service (${serviceCount})`" aria-label="Find a service" />
                </div>
                <div class="relative">
                  <div class="max-h-[17rem] overflow-y-auto overscroll-contain pb-2">
                    <template v-for="group in mobileServiceGroups" :key="group.name">
                      <p v-if="mobileServiceGroups.length > 1" class="sticky top-0 z-[1] px-4 pt-3 pb-1 text-[12px] font-bold uppercase tracking-[0.1em] s-subtle s-bg-alt">{{ group.name }}</p>
                      <Link v-for="s in group.services" :key="s.href" :href="s.href" class="block px-4 py-2.5 text-[16px] s-text border-b s-border last:border-b-0" @click="mobileOpen = false">{{ s.name }}</Link>
                    </template>
                    <p v-if="!mobileServiceGroups.length" class="px-4 py-4 text-[15px] s-muted">No service matches “{{ mobileServiceQuery }}”.</p>
                  </div>
                  <div v-if="serviceCount > 6" class="pointer-events-none absolute inset-x-0 bottom-0 h-8" style="background: linear-gradient(to bottom, transparent, var(--s-bg-alt))"></div>
                </div>
                <div class="p-3 grid grid-cols-2 gap-2 border-t s-border">
                  <Link href="/services" class="btn btn-secondary btn-sm" @click="mobileOpen = false">All services</Link>
                  <Link href="/pricing" class="btn btn-secondary btn-sm" @click="mobileOpen = false">Price list</Link>
                </div>
              </div>
            </div>

            <div v-if="showAreasMenu">
              <button type="button" @click="mobileSection = mobileSection === 'locations' ? null : 'locations'" class="w-full flex items-center justify-between py-3.5 text-[16px] font-semibold s-heading" :aria-expanded="mobileSection === 'locations'">
                Areas
                <svg :class="['w-4 h-4 s-subtle transition-transform', mobileSection === 'locations' && 'rotate-180']" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
              </button>
              <div v-if="mobileSection === 'locations'" class="mb-3 rounded-lg border s-border s-bg-alt">
                <div v-if="topLocations.length > 8" class="px-3 pt-3">
                  <input v-model="mobileAreaQuery" type="search" class="input !py-2 !text-[15px]" :placeholder="`Find your area (${topLocations.length})`" aria-label="Find your area" />
                </div>
                <div class="relative">
                  <div class="max-h-[17rem] overflow-y-auto overscroll-contain pb-2">
                    <Link v-for="l in mobileAreas" :key="l.href" :href="l.href" class="block px-4 py-2.5 text-[16px] s-text border-b s-border last:border-b-0" @click="mobileOpen = false">{{ l.name }}</Link>
                    <p v-if="!mobileAreas.length" class="px-4 py-4 text-[15px] s-muted">No area matches “{{ mobileAreaQuery }}”.</p>
                  </div>
                  <div v-if="topLocations.length > 6" class="pointer-events-none absolute inset-x-0 bottom-0 h-8" style="background: linear-gradient(to bottom, transparent, var(--s-bg-alt))"></div>
                </div>
                <div class="p-3 border-t s-border">
                  <Link href="/locations" class="btn btn-secondary btn-sm w-full" @click="mobileOpen = false">Every area we cover</Link>
                </div>
              </div>
            </div>

            <Link v-for="item in primaryAfter" :key="item.href" :href="item.href" :class="mobileClass(item.href)" @click="mobileOpen = false">{{ item.label }}</Link>
          </div>

          <div class="mt-3 mb-2 p-4 rounded-lg s-bg-alt border s-border space-y-3">
            <a v-if="company.tel" :href="'tel:' + tel" class="flex items-center gap-3 text-[16px] font-bold s-heading">
              <span class="icon-box !w-9 !h-9"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" :d="icons.phone" /></svg></span>
              {{ company.phone }}
            </a>
            <a v-if="company.email" :href="'mailto:' + company.email" class="flex items-center gap-3 text-[15.5px] s-muted">
              <span class="icon-box !w-9 !h-9"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" :d="icons.mail" /></svg></span>
              <span class="truncate">{{ company.email }}</span>
            </a>
            <WhatsAppButton green class="w-full">Get a free quote on WhatsApp</WhatsAppButton>
          </div>
        </nav>
      </div>
    </header>

    <!-- At least one screen tall: the footer starts below the fold, so content that renders a frame later never pushes it (no layout shift) -->
    <main id="main" class="flex-1 min-h-screen">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="mt-auto band-navy">
      <div class="container-app pt-16 pb-10">
        <div class="grid grid-cols-2 lg:grid-cols-[1.35fr_1fr_0.8fr_1.25fr] gap-x-8 gap-y-12">
          <!-- Brand -->
          <div class="col-span-2 lg:col-span-1">
            <Link href="/" class="inline-flex rounded-lg bg-white p-2" :aria-label="company.name + ' home'">
              <img :src="img(company.footer_logo || company.logo, 240)" :alt="company.name" width="130" height="60" class="h-14 w-auto object-contain" loading="lazy" />
            </Link>
            <p v-if="company.summary" class="mt-6 text-[15px] text-white/70 leading-relaxed max-w-sm">{{ company.summary }}</p>
            <div class="mt-6 flex flex-wrap gap-2.5">
              <a v-if="company.tel" :href="'tel:' + tel" class="btn btn-sm btn-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" :d="icons.phone" /></svg>
                Call now
              </a>
              <WhatsAppButton size="sm" green>WhatsApp</WhatsAppButton>
            </div>
            <div v-if="socialLinks.length" class="mt-6 flex flex-wrap gap-2">
              <a v-for="s in socialLinks" :key="s.href" :href="s.href" target="_blank" rel="noopener" :aria-label="s.label" class="w-10 h-10 rounded-md bg-white/[0.07] text-white/75 hover:bg-white hover:text-[#0b1f33] grid place-items-center transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path :d="s.icon" /></svg>
              </a>
            </div>
          </div>

          <!-- Services -->
          <div>
            <p class="footer-title">Door services</p>
            <ul class="space-y-3 text-[15px] text-white/70">
              <li v-for="s in footerTopServices" :key="s.href"><Link :href="s.href" class="hover:text-white transition-colors">{{ s.name }}</Link></li>
              <li><Link href="/services" class="text-[#9ec9f5] hover:text-white font-semibold">All services →</Link></li>
            </ul>
          </div>

          <!-- Company -->
          <div>
            <p class="footer-title">Company</p>
            <ul class="space-y-3 text-[15px] text-white/70">
              <li v-for="l in footerCompany" :key="l.href"><Link :href="l.href" class="hover:text-white transition-colors">{{ l.label }}</Link></li>
              <li><Link href="/pricing" class="hover:text-white transition-colors">Price list</Link></li>
            </ul>
          </div>

          <!-- Contact -->
          <div class="col-span-2 lg:col-span-1">
            <p class="footer-title">Get in touch</p>
            <ul class="space-y-4 text-[15px] text-white/75">
              <li v-if="company.tel">
                <a :href="'tel:' + tel" class="flex items-center gap-3.5 group">
                  <span class="w-10 h-10 rounded-md bg-white/[0.08] text-[#9ec9f5] grid place-items-center shrink-0"><svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" :d="icons.phone" /></svg></span>
                  <span><span class="block text-[12.5px] text-white/50">Phone &amp; WhatsApp</span><span class="block text-[17px] font-bold text-white group-hover:text-[#9ec9f5]">{{ company.phone }}</span></span>
                </a>
              </li>
              <li v-if="company.email">
                <a :href="'mailto:' + company.email" class="flex items-center gap-3.5 group">
                  <span class="w-10 h-10 rounded-md bg-white/[0.08] text-[#9ec9f5] grid place-items-center shrink-0"><svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" :d="icons.mail" /></svg></span>
                  <span class="min-w-0"><span class="block text-[12.5px] text-white/50">Email</span><span class="block break-all group-hover:text-white">{{ company.email }}</span></span>
                </a>
              </li>
              <li v-if="company.address" class="flex items-start gap-3.5">
                <span class="w-10 h-10 rounded-md bg-white/[0.08] text-[#9ec9f5] grid place-items-center shrink-0"><svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" :d="icons.pin" /></svg></span>
                <span><span class="block text-[12.5px] text-white/50">Address</span><span class="block leading-relaxed">{{ company.address }}</span></span>
              </li>
              <li v-if="company.hours" class="flex items-center gap-3.5">
                <span class="w-10 h-10 rounded-md bg-white/[0.08] text-[#9ec9f5] grid place-items-center shrink-0"><svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" :d="icons.clock" /></svg></span>
                <span><span class="block text-[12.5px] text-white/50">Opening hours</span><span class="block">{{ company.hours }}</span></span>
              </li>
            </ul>
          </div>
        </div>

        <div v-if="topLocations.length" class="mt-12 pt-8 border-t border-white/10">
          <p class="footer-title">Areas we serve</p>
          <div class="flex flex-wrap gap-2 text-[14px]">
            <Link v-for="l in topLocations" :key="l.href" :href="l.href" class="px-3 py-1.5 rounded-md bg-white/[0.06] text-white/75 hover:bg-white/15 hover:text-white">{{ l.name }}</Link>
          </div>
        </div>
      </div>

      <div class="border-t border-white/10 bg-black/20">
        <div class="container-app py-5 flex flex-col sm:flex-row justify-between gap-3 text-[14px] text-white/55">
          <p>{{ company.copyright }}</p>
          <div class="flex flex-wrap gap-x-6 gap-y-2">
            <Link href="/privacy-policy" class="hover:text-white">Privacy Policy</Link>
            <Link href="/terms-of-service" class="hover:text-white">Terms of Service</Link>
            <a href="/sitemap.xml" class="hover:text-white">Sitemap</a>
          </div>
        </div>
      </div>
    </footer>

    <!-- WhatsApp -->
    <a v-if="company.whatsapp" :href="whatsappUrl" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"
      class="right-5 hidden lg:flex fixed z-40 bottom-5 w-14 h-14 rounded-full bg-[#16a34a] hover:bg-[#15803d] text-white items-center justify-center shadow-[0_10px_30px_-6px_rgba(18,140,74,0.55)] transition">
      <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path :d="icons.whatsapp" /></svg>
    </a>

    <Lightbox />
    <SearchOverlay :open="searchOpen" @close="searchOpen = false" />

    <!-- Mobile action bar: call and WhatsApp side by side, search on the edge -->
    <nav class="lg:hidden fixed inset-x-0 bottom-0 z-40 border-t s-border" style="background: var(--s-surface); padding-bottom: env(safe-area-inset-bottom)" aria-label="Quick actions">
      <div class="flex items-stretch gap-2 px-3 py-2 max-w-lg mx-auto">
        <button type="button" @click="searchOpen = true" class="w-11 h-11 shrink-0 grid place-items-center rounded-md s-muted border s-border" aria-label="Search the website">
          <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M21 21l-5.2-5.2M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" /></svg>
        </button>
        <a v-if="company.tel" :href="'tel:' + tel" class="btn btn-primary flex-1 !h-11 !px-3 !text-[15px]">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" :d="icons.phone" /></svg>
          Call now
        </a>
        <Link v-else href="/contact" class="btn btn-secondary flex-1 !h-11 !text-[15px]">Contact</Link>
        <WhatsAppButton size="sm" green class="flex-1 !h-11 !text-[15px]">WhatsApp</WhatsAppButton>
      </div>
    </nav>

    <!-- Toast -->
    <transition enter-active-class="transition duration-200" enter-from-class="opacity-0 translate-y-2" leave-active-class="transition duration-150" leave-to-class="opacity-0">
      <div v-if="toast" class="fixed z-50 top-24 right-5 left-5 sm:left-auto sm:w-96 card p-4 flex gap-3" style="box-shadow: var(--s-shadow-lg)" role="status">
        <span :class="['w-8 h-8 rounded-full flex items-center justify-center shrink-0 text-white', toast.type === 'error' ? 'bg-[#dc2626]' : 'bg-[#16a34a]']">{{ toast.type === 'error' ? '!' : '✓' }}</span>
        <p class="text-sm s-text pt-1.5 flex-1">{{ toast.message }}</p>
        <button @click="toast = null" class="s-subtle text-sm" aria-label="Close">✕</button>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import Lightbox from '@/Components/Site/Lightbox.vue';
import SearchOverlay from '@/Components/Site/SearchOverlay.vue';
import WhatsAppButton from '@/Components/Site/WhatsAppButton.vue';
import { img } from '@/utils/img';

const page = usePage();
const company = computed(() => page.props.company || {});
const meta = computed(() => page.props.meta);
const serviceCategories = computed(() => page.props.serviceCategories || []);
const topLocations = computed(() => page.props.topLocations || []);
// The header menu gets an Areas dropdown once there are enough area pages to make it useful;
// the footer always lists them (internal links for local SEO).
const showAreasMenu = computed(() => topLocations.value.length >= 4);
const footerTopServices = computed(() => page.props.footerTopServices || []);
const tel = computed(() => company.value.tel || '');
const whatsappUrl = computed(() => {
  const w = company.value.whatsapp || '';
  if (w.startsWith('http')) return w;
  return 'https://wa.me/' + w.replace(/\D/g, '');
});

const primaryBefore = [
  { label: 'Home', href: '/' },
  { label: 'About', href: '/about' },
];
const primaryAfter = [
  { label: 'Pricing', href: '/pricing' },
  { label: 'Projects', href: '/projects' },
  { label: 'Door tips', href: '/blogs' },
  { label: 'Contact', href: '/contact' },
];
const footerCompany = [
  { label: 'About us', href: '/about' },
  { label: 'Our projects', href: '/projects' },
  { label: 'Customer reviews', href: '/reviews' },
  { label: 'Areas we serve', href: '/locations' },
  { label: 'Door care guides', href: '/blogs' },
  { label: 'Contact us', href: '/contact' },
];

const open = ref(null);
const mobileOpen = ref(false);
const mobileSection = ref(null);
const serviceCount = computed(() => serviceCategories.value.reduce((n, g) => n + g.services.length, 0));
// Mobile menu: filter the service and area lists by name.
const mobileServiceQuery = ref('');
const mobileAreaQuery = ref('');
const mobileAreas = computed(() => {
  const q = mobileAreaQuery.value.trim().toLowerCase();
  return q ? topLocations.value.filter((l) => l.name.toLowerCase().includes(q)) : topLocations.value;
});
const mobileServiceGroups = computed(() => {
  const q = mobileServiceQuery.value.trim().toLowerCase();
  return serviceCategories.value
    .map((g) => ({ ...g, services: q ? g.services.filter((s) => s.name.toLowerCase().includes(q)) : g.services }))
    .filter((g) => g.services.length);
});
let closeTimer = null;
// A short delay lets the pointer travel from the menu button into the panel.
const openMenu = name => { clearTimeout(closeTimer); open.value = name; };
const closeMenuSoon = () => { clearTimeout(closeTimer); closeTimer = setTimeout(() => { open.value = null; }, 160); };
const toggle = name => { clearTimeout(closeTimer); open.value = open.value === name ? null : name; };

const path = computed(() => (page.url || '/').split('?')[0]);
const isActive = href => (href === '/' ? path.value === '/' : path.value === href || path.value.startsWith(href + '/') || (href === '/services' && path.value.startsWith('/service/')));
// Active page: blue text with a bar along the header's bottom edge.
const navClass = href => ['relative h-full inline-flex items-center whitespace-nowrap px-2.5 2xl:px-3 text-[15px] font-semibold transition-colors after:absolute after:left-2.5 after:right-2.5 after:bottom-0 after:h-[2px]',
  isActive(href) ? 'text-[var(--s-accent-text)] after:bg-[var(--s-accent)]' : 's-heading hover:text-[var(--s-accent-text)]'];
const mobileClass = href => ['block py-3.5 text-[16px] font-semibold', isActive(href) ? 's-accent' : 's-heading'];

watch(() => page.url, () => { mobileOpen.value = false; mobileSection.value = null; open.value = null; searchOpen.value = false; });

// Conversion events for GTM (switched on in admin): calls, WhatsApp and email clicks.
function trackClick(e) {
  if (!window.__trackEvents) return;
  const a = e.target.closest?.('a[href]');
  if (!a) return;
  const href = a.getAttribute('href') || '';
  const event = href.startsWith('tel:') ? 'click_call' : /wa\.me|wa\.link|whatsapp\.com/i.test(href) ? 'click_whatsapp' : href.startsWith('mailto:') ? 'click_email' : null;
  if (!event) return;
  (window.dataLayer = window.dataLayer || []).push({ event, link_url: href, page_path: location.pathname });
}
onMounted(() => document.addEventListener('click', trackClick, true));
onBeforeUnmount(() => document.removeEventListener('click', trackClick, true));

// Search: header button, Ctrl/Cmd+K or "/"
const searchOpen = ref(false);
function onSearchKey(e) {
  const typing = /input|textarea|select/i.test(e.target?.tagName || '') || e.target?.isContentEditable;
  if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') { e.preventDefault(); searchOpen.value = true; }
  else if (e.key === '/' && !typing) { e.preventDefault(); searchOpen.value = true; }
}
onMounted(() => document.addEventListener('keydown', onSearchKey));
onBeforeUnmount(() => document.removeEventListener('keydown', onSearchKey));

// Scroll shadow on the header
// A tiny marker at the top of the page is watched instead of reading the scroll position,
// so the browser never has to recalculate layout for it.
const scrolled = ref(false);
const topMarker = ref(null);
let topObserver;
onMounted(() => {
  if (!topMarker.value || !('IntersectionObserver' in window)) return;
  topObserver = new IntersectionObserver(([entry]) => { scrolled.value = !entry.isIntersecting; });
  topObserver.observe(topMarker.value);
});
onBeforeUnmount(() => topObserver?.disconnect());

// Flash messages (e.g. after sending the contact form)
const toast = ref(null);
let toastTimer = null;
watch(() => page.props.flash, (flash) => {
  // Forms show their own success message; the toast is only for problems.
  const message = flash?.error;
  if (!message) return;
  toast.value = { type: flash?.error ? 'error' : 'success', message };
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => { toast.value = null; }, 7000);
}, { immediate: true });

const icons = {
  mail: 'M3 8l7.9 5.3a2 2 0 002.2 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
  clock: 'M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z',
  star: 'M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.07 3.3a1 1 0 00.95.69h3.47c.97 0 1.37 1.24.59 1.81l-2.81 2.04a1 1 0 00-.36 1.12l1.07 3.3c.3.92-.76 1.69-1.54 1.12l-2.81-2.04a1 1 0 00-1.18 0l-2.8 2.04c-.79.57-1.84-.2-1.55-1.12l1.08-3.3a1 1 0 00-.37-1.12L2.97 8.73c-.78-.57-.38-1.81.59-1.81h3.47a1 1 0 00.95-.69l1.07-3.3z',
  pin: 'M12 21s-7-6.2-7-11.5A7 7 0 0112 2.5a7 7 0 017 7C19 14.8 12 21 12 21zm0-9a2.5 2.5 0 100-5 2.5 2.5 0 000 5z',
  phone: 'M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.5 4.5a1 1 0 01-.5 1.2l-2.26 1.13a11 11 0 005.52 5.52l1.13-2.26a1 1 0 011.2-.5l4.5 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.7 21 3 14.3 3 6V5z',
  whatsapp: 'M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.64.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.49s1.07 2.89 1.22 3.09c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.7.63.71.22 1.36.19 1.87.12.57-.09 1.76-.72 2-1.41.25-.7.25-1.29.17-1.41-.07-.13-.27-.2-.57-.35zM12.04 21.5h-.01a9.45 9.45 0 01-4.82-1.32l-.35-.2-3.58.94.96-3.49-.23-.36a9.43 9.43 0 01-1.45-5.03C2.56 6.83 6.8 2.6 12.04 2.6c2.54 0 4.92.99 6.72 2.78a9.42 9.42 0 012.78 6.71c0 5.23-4.25 9.41-9.5 9.41zm8.08-17.5A11.33 11.33 0 0012.04.67C5.74.67.61 5.8.61 12.1c0 2.01.53 3.98 1.53 5.71L.52 23.76l6.07-1.59a11.4 11.4 0 005.45 1.39h.01c6.3 0 11.43-5.13 11.43-11.43 0-3.05-1.19-5.92-3.36-8.08z',
};

const socialIcons = {
  facebook: 'M24 12.07C24 5.45 18.63.07 12 .07S0 5.45 0 12.07c0 5.99 4.39 10.95 10.13 11.85v-8.38H7.08v-3.47h3.05V9.43c0-3.01 1.79-4.67 4.53-4.67 1.31 0 2.69.24 2.69.24v2.95h-1.52c-1.49 0-1.96.93-1.96 1.87v2.25h3.33l-.53 3.47h-2.8v8.38C19.61 23.02 24 18.06 24 12.07z',
  instagram: 'M12 2.16c3.2 0 3.58.01 4.85.07 3.25.15 4.77 1.69 4.92 4.92.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.15 3.23-1.66 4.77-4.92 4.92-1.27.06-1.64.07-4.85.07s-3.58-.01-4.85-.07c-3.26-.15-4.77-1.7-4.92-4.92-.06-1.27-.07-1.64-.07-4.85s.01-3.58.07-4.85C2.38 3.92 3.9 2.38 7.15 2.23 8.42 2.17 8.8 2.16 12 2.16zM12 0C8.74 0 8.33.01 7.05.07 2.7.27.27 2.69.07 7.05.01 8.33 0 8.74 0 12s.01 3.67.07 4.95c.2 4.36 2.62 6.78 6.98 6.98 1.28.06 1.69.07 4.95.07s3.67-.01 4.95-.07c4.35-.2 6.78-2.62 6.98-6.98.06-1.28.07-1.69.07-4.95s-.01-3.67-.07-4.95C23.73 2.7 21.31.27 16.95.07 15.67.01 15.26 0 12 0zm0 5.84a6.16 6.16 0 100 12.32 6.16 6.16 0 000-12.32zM12 16a4 4 0 110-8 4 4 0 010 8zm6.41-11.85a1.44 1.44 0 100 2.88 1.44 1.44 0 000-2.88z',
  linkedin: 'M20.45 20.45h-3.55v-5.57c0-1.33-.03-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 110-4.13 2.06 2.06 0 010 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.73v20.54C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.73V1.73C24 .77 23.2 0 22.22 0z',
};
socialIcons.x = 'M18.24 2.25h3.31l-7.23 8.26 8.5 11.24h-6.66l-5.21-6.82-5.97 6.82H1.67l7.73-8.84L1.25 2.25h6.83l4.71 6.23 5.45-6.23zm-1.16 17.52h1.83L7.08 4.13H5.12l11.96 15.64z';
socialIcons.youtube = 'M23.5 6.2a3 3 0 00-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 00.5 6.2 31.4 31.4 0 000 12a31.4 31.4 0 00.5 5.8 3 3 0 002.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 002.1-2.1A31.4 31.4 0 0024 12a31.4 31.4 0 00-.5-5.8zM9.6 15.6V8.4l6.3 3.6-6.3 3.6z';
socialIcons.tiktok = 'M19.6 6.7a4.8 4.8 0 01-3.8-4.2V2h-3.4v13.6a2.9 2.9 0 11-2-2.7V9.4a6.3 6.3 0 105.4 6.2V8.7a8.2 8.2 0 003.8 1V6.7z';
socialIcons.pinterest = 'M12 0a12 12 0 00-4.4 23.2c-.1-.9-.2-2.4 0-3.4l1.4-6s-.4-.7-.4-1.8c0-1.7 1-2.9 2.2-2.9 1 0 1.5.8 1.5 1.7 0 1-.7 2.6-1 4-.3 1.2.6 2.2 1.8 2.2 2.1 0 3.8-2.2 3.8-5.5 0-2.9-2.1-4.9-5-4.9-3.4 0-5.4 2.6-5.4 5.2 0 1 .4 2.1.9 2.7.1.1.1.2.1.3l-.3 1.4c-.1.2-.2.3-.4.2-1.5-.7-2.4-2.9-2.4-4.6 0-3.8 2.7-7.2 7.9-7.2 4.1 0 7.3 2.9 7.3 6.9 0 4.1-2.6 7.4-6.2 7.4-1.2 0-2.4-.6-2.8-1.4l-.7 2.9c-.3 1-1 2.3-1.5 3.1A12 12 0 1012 0z';
socialIcons.google = 'M12.24 10.29v3.67h5.2c-.22 1.34-1.58 3.93-5.2 3.93-3.13 0-5.68-2.59-5.68-5.79s2.55-5.79 5.68-5.79c1.78 0 2.97.76 3.66 1.41l2.49-2.4C16.79 3.84 14.74 2.9 12.24 2.9 7.21 2.9 3.14 6.97 3.14 12s4.07 9.1 9.1 9.1c5.25 0 8.74-3.69 8.74-8.89 0-.6-.07-1.05-.15-1.51h-8.59z';
const socialLabels = { google: 'Google reviews', x: 'X', youtube: 'YouTube', tiktok: 'TikTok', linkedin: 'LinkedIn' };
const socialLinks = computed(() => Object.entries(company.value.socials || {})
  .map(([k, href]) => ({ href, label: socialLabels[k] || k[0].toUpperCase() + k.slice(1), icon: socialIcons[k] }))
  .filter(s => s.icon));
</script>
