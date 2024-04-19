<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import { avatarText } from '@core/utils/formatters'
import { tr } from "vuetify/locale"
const notifications =['fasf','rewtw','mhju']
const props = defineProps({
  notifications: {
    type: Array,
    required: true,
  },
  badgeProps: {
    type: null,
    required: false,
    default: undefined,
  },
  location: {
    type: null,
    required: false,
    default: 'bottom end',
  },
})

</script>

<template>
  <div>
    <IconBtn id="notification-btn">
      <VBadge
        v-bind="props.badgeProps"
        color="error"
        :content="AllNotifications?AllNotifications.length:'0'"
        class="notification-badge"
      >
        <VIcon
          size="26"
          icon="tabler-bell"
        />
      </VBadge>

      <VMenu
        activator="parent"
        width="380px"
        :location="props.location"
        offset="14px"
        :close-on-content-click="false"
      >
        <VCard class="d-flex flex-column">
          <!-- 👉 Header -->
          <VCardItem class="notification-section">
            <VCardTitle class="text-lg">
              Notifications
            </VCardTitle>

            <template #append>
              <IconBtn

                @click="markAllReadOrUnread"
              >
                <VIcon :icon="!isAllMarkRead ? 'tabler-mail' : 'tabler-mail-opened' " />

                <VTooltip
                  activator="parent"
                  location="start"
                >
                  {{ !isAllMarkRead ? 'Mark all as unread' : 'Mark all as read' }}
                </VTooltip>
              </IconBtn>
            </template>
          </VCardItem>

          <VDivider />

          <!-- 👉 Notifications list -->
          <PerfectScrollbar
            :options="{ wheelPropagation: true }"
            style="max-block-size: 23.75rem;"
          >
            <VList class="notification-list rounded-0 py-0">
              <template
                v-for="(notification, index) in AllNotifications"
                :key="notification.title"
              >
                <VDivider v-if="index > 0" />
                <VListItem
                  link
                  lines="one"
                  min-height="66px"
                  class="list-item-hover-class"
                  @click="$emit('click:notification', notification)"
                >

                  <template #prepend>
                    <VListItemAction start>
                      <VAvatar
                        size="40"

                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-telegram" width="30" height="35" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                          <path d="M15 10l-4 4l6 6l4 -16l-18 7l4 2l2 6l3 -4" />
                        </svg>
                      </VAvatar>
                    </VListItemAction>
                  </template>

                  <VListItemTitle>{{ notification.data }}</VListItemTitle>
                  <VListItemSubtitle>{{notification.sent_to}}</VListItemSubtitle>
                  <span class="text-xs text-disabled">{{notification.notifiable_type}}</span>

                  <!-- Slot: Append -->
                  <template #append>
                    <div class="d-flex flex-column align-center gap-4">
                      <VBadge
                        dot
                        :color="!notification.isSeen ? 'primary' : '#a8aaae'"
                        :class="`${notification.isSeen ? 'visible-in-hover' : ''} ms-1`"
                        @click.stop="$emit(notification.isSeen ? 'unread' : 'read', [notification.id])"
                      />

                      <div style="block-size: 28px; inline-size: 28px;">
                        <IconBtn
                          size="small"
                          class="visible-in-hover"
                          @click="$emit('remove', notification.id)"
                        >
                          <VIcon
                            size="20"
                            icon="tabler-x"
                          />
                        </IconBtn>
                      </div>
                    </div>
                  </template>
                </VListItem>
              </template>

              <VListItem
                v-show="Notifications.length <= 0"
                class="text-center text-medium-emphasis"
                style="block-size: 56px;"
              >
                <VListItemTitle >No Notification Found!</VListItemTitle>
              </VListItem>
            </VList>
          </PerfectScrollbar>

          <VDivider />

          <!-- 👉 Footer -->
          <VCardActions

            class="notification-footer"
          >
            <VBtn
              block
              @click="GoToNotifications"
            >
              View All Notifications
            </VBtn>
          </VCardActions>
        </VCard>
      </VMenu>
    </IconBtn>

  </div>
</template>

<script>
import router from "@/router"
import axios from "@axios"

export default {
  data(){
    return{
      Notifications:[],
      AllNotifications:[],
    }
  },
  mounted() {
    this.getNotification()
    this.getAllNotification()
  },
  methods: {
    async GoToNotifications(){
      await router.push({ name: 'All_Notification' })
    },
    getNotification() {
      axios
        .get(`${window.location.origin}/api/getLast5Notification`)
        .then(res => this.Notifications = res.data.data)
    },
    getAllNotification() {
      axios
        .get(`${window.location.origin}/api/notification`)
        .then(res => this.AllNotifications = res.data.data)
    },
  },
}
</script>

<style lang="scss">
.notification-section {
  padding: 14px !important;
}

.notification-footer {
  padding: 6px !important;
}

.list-item-hover-class {
  .visible-in-hover {
    display: none;
  }

  &:hover {
    .visible-in-hover {
      display: block;
    }
  }
}

.notification-list.v-list {
  .v-list-item {
    border-radius: 0 !important;
    margin: 0 !important;
  }
}

// Badge Style Override for Notification Badge
.notification-badge {
  .v-badge__badge {
    /* stylelint-disable-next-line liberty/use-logical-spec */
    min-width: 18px;
    padding: 0;
    block-size: 18px;
  }
}
</style>
