<template>
  <!-- Unread enquiries at the top of the dashboard: answer on WhatsApp or call in one click -->
  <section class="admin-card overflow-hidden mb-6">
    <header class="flex items-center justify-between gap-3 px-5 py-4 border-b a-border">
      <div class="flex items-center gap-3 min-w-0">
        <span class="w-9 h-9 rounded-lg grid place-items-center shrink-0 a-tint-accent a-accent">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.9 5.3a2 2 0 002.2 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
        </span>
        <div class="min-w-0">
          <h3 class="font-bold">New enquiries</h3>
          <p class="text-xs a-muted">{{ items.length ? `${unread} from the last 30 days waiting for a reply. Customers usually ask two or three companies, so reply fast.` : 'All caught up for the last 30 days. New enquiries appear here.' }}</p>
        </div>
      </div>
      <Link href="/admin/messages?unread=true" class="admin-btn-secondary a-btn-sm shrink-0">Open inbox</Link>
    </header>

    <ul v-if="items.length" class="a-divide">
      <li v-for="m in items" :key="m.id" class="px-5 py-4 flex flex-col md:flex-row md:items-center gap-3 md:gap-5">
        <div class="min-w-0 flex-1">
          <p class="flex items-center gap-2 flex-wrap">
            <span class="font-semibold">{{ m.name || 'Unknown' }}</span>
            <span class="text-xs a-subtle">{{ ago(m.at) }}</span>
            <span v-if="m.subject" class="a-badge">{{ m.subject }}</span>
          </p>
          <p v-if="m.text" class="mt-1 text-sm a-muted line-clamp-2">{{ m.text }}</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
          <a v-if="m.phone" :href="waLink(m)" target="_blank" rel="noopener" @click="markRead(m)" class="admin-btn-primary a-btn-sm !bg-[#16a34a] hover:!bg-[#15803d]">WhatsApp</a>
          <a v-if="m.phone" :href="'tel:' + digits(m.phone)" @click="markRead(m)" class="admin-btn-secondary a-btn-sm">Call</a>
          <a v-else-if="m.email" :href="`mailto:${m.email}?subject=${encodeURIComponent('Re: ' + (m.subject || 'Your enquiry'))}`" @click="markRead(m)" class="admin-btn-secondary a-btn-sm">Email</a>
          <Link :href="`/admin/messages?open=${m.id}`" class="a-btn-ghost a-btn-sm">Open</Link>
        </div>
      </li>
    </ul>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { usePermissions } from '@/Composables/usePermissions';

const props = defineProps({ inbox: { type: Object, required: true } });
const { can } = usePermissions();
const brand = usePage().props.admin?.brand || 'our team';
const items = ref([...(props.inbox.items || [])]);
const unread = ref(props.inbox.unread || 0);

const digits = (phone) => {
  const d = String(phone || '').replace(/\D/g, '');
  return d.length === 8 ? '65' + d : d; // Singapore numbers are often saved without the country code
};
const waLink = (m) => `https://wa.me/${digits(m.phone)}?text=${encodeURIComponent(`Hi ${m.name || ''}, this is ${brand} about your enquiry. `.replace('Hi , ', 'Hi, '))}`;

// Replying counts as reading: the enquiry leaves this list and the unread count drops.
function markRead(m) {
  if (!can('enquiries.edit')) return;
  router.post(`/admin/messages/${m.id}/read`, {}, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      items.value = items.value.filter((x) => x.id !== m.id);
      unread.value = Math.max(0, unread.value - 1);
    },
  });
}

function ago(iso) {
  if (!iso) return '';
  const mins = Math.round((Date.now() - new Date(iso).getTime()) / 60000);
  if (mins < 60) return `${Math.max(1, mins)} min ago`;
  if (mins < 1440) return `${Math.round(mins / 60)} h ago`;
  return new Date(iso).toLocaleDateString('en-SG', { day: 'numeric', month: 'short' });
}
</script>
