<script setup>
const shortcuts = [
  {
    icon: 'tabler-user',
    title: 'Guests',
    subtitle: 'Guest list',
    to: { name: 'filter-guest' },
  },
  {
    icon: 'tabler-file',
    title: 'Reservations',
    subtitle: 'Reservation list',
    to: { name: 'reservations' },
  },
  {
    icon: 'tabler-user',
    title: 'Company',
    subtitle: 'Company list',
    to: { name: 'Company-company_profile' },
  },
  {
    icon: 'tabler-layout',
    title: 'rack',
    subtitle: 'list of rooms',
    to: { name: 'Rack' },
  },
  {
    icon: 'tabler-layout',
    title: 'Rooms by Floor',
    subtitle: 'Floor Chart',
    to: {
      name: 'rooms_by_floor',

    },
  },
  {
    icon: 'tabler-help',
    title: 'Cashier Closer',
    subtitle: 'Cashier details',
    to: { name: 'cashier-closer' },
  },
]
</script>

<template>
  <Shortcuts :shortcuts="shortcuts" />
</template>
