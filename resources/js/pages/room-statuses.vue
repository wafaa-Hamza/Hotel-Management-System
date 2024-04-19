
<template>
  <div>

    <Breadcrumb  class="d-print-none" ></Breadcrumb>
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div style="width: 65%;display: flex;justify-content: space-between">
        <VSelect item-color="customColorValue" :label="$t('row')" :items="['10', '20', '30', '40']" variant="solo" style="width: 18%;" />
        <VSpacer />
      </div>
      <VSpacer />
      <div>
        <VBtn class="btn mx-4">
          {{ $t('export') }}
        </VBtn>
        <VBtn class="btn mx-4">
          {{ $t('PDF') }}
        </VBtn>
      </div>
    </div>
    <VTable>
      <thead>
        <tr>
          <th>
            {{ $t('Name') }}
          </th>
          <th> {{ $t('Reservation status code') }} </th>
          <th>{{ $t('Color') }}</th>
          <th>{{ $t('Update') }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="reservation in room_statuses" :key="reservation.id">
          <td v-if="$i18n.locale === 'en'">
            {{ reservation.name }}
          </td>
          <td v-else>
            {{ reservation.name_loc }}
          </td>
          <td>
            {{ reservation.status_code }}
          </td>
          <td>
            <VChip :style="{ 'background-color': reservation.color, 'width': '50px' }" variant="elevated">
            </VChip>
          </td>
          <td>
            <VBtn color="primary">
              <VRow>
                <VDialog v-model="reservation.dialogVisible" width="1024">
                  <template #activator="{ props }">
                    <VBtn v-bind="props" @click="Updates(reservation)">
                      <VIcon icon="mdi-file-edit-outline" />
                    </VBtn>
                  </template>
                  <VCard>
                    <VCardTitle>
                      <span class="text-h5">{{ $t('Update') }}</span>
                    </VCardTitle>
                    <VCardText>
                      <VContainer>
                        <VRow justify="center">
                          <VColorPicker v-model="color" class="ma-2 d-flex justify-content-center" hide-inputs
                            show-swatches></VColorPicker>
                        </VRow>
                      </VContainer>
                    </VCardText>
                    <VCardActions>
                      <VSpacer />
                      <VBtn color="blue-darken-1" variant="text"
                        @click="reservation.dialogVisible = false, update_errors = null">
                        {{ $t('Close') }}
                      </VBtn>
                      <VBtn color="blue-darken-1" variant="text" @click="Update">
                        {{ $t('Update') }}
                      </VBtn>
                    </VCardActions>
                  </VCard>
                </VDialog>
              </VRow>
            </VBtn>
          </td>
        </tr>
      </tbody>
    </VTable>
  </div>
</template>

<script>
import { useRoomstatusStore } from '../stores/RoomstatusStore'
import { mapStores, mapActions } from 'pinia'
import Swal from 'sweetalert2'

export default {
  name: 'room-statuses',
  data() {
    return {
      dialog: false,
      editFlag: false,
      isSnackbarVisible: ref(false),
      color: null,
      reservation_id: null,
    }
  },
  computed: {
    ...mapStores(useRoomstatusStore),

    room_statuses() {
      return this.roomstatusStore.room_statuses
    },
  },
  watch: {
    room_statuses() {
    },
  },
  methods: {
    ...mapActions(useRoomstatusStore, ['editRoomStatus', 'GetRoomStatuses']),

    Update() {
      this.editRoomStatus({
        color: this.color,
      }, this.reservation_id)
      this.dialog = false
      this.UpdateAlert()
    },

    Updates(reservation) {
      this.dialog = true
      this.reservation_id = reservation.id
      this.color = reservation.color
    },
    UpdateAlert() {
      Swal.fire({
        position: this.$i18n.locale === 'en' ? 'top-end' : 'top-start',
        icon: 'success',
        title: 'Room status color has been updated',
        showConfirmButton: false,
        timer: 2000,
      })
    },

  },
  created() {
    this.GetRoomStatuses()
  },

  // eslint-disable-next-line vue/component-api-style
}
</script>


