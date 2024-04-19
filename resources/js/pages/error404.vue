<script setup>
const props = defineProps({
    errorCode: {
        type: String,
        required: false,
    },
    errorTitle: {
        type: String,
        required: false,
    },
    errorDescription: {
        type: String,
        required: false,
    },
})
definePage({
    meta: {
        layout: 'blank',
    },
})
</script>
<template>
    <div class="text-center">
        <ErrorHeader error-title="Page Not Found :("
            error-description="We couldn't find the page you are looking for." />
        <!-- 👉 Title and subtitle -->
        <h1 v-if="props.errorCode" class="text-h1 font-weight-medium">
            {{ props.errorCode }}
        </h1>
        <h4 v-if="props.errorTitle" class="text-h4 font-weight-medium mb-3">
            {{ props.errorTitle }}
        </h4>
        <p v-if="props.errorDescription">
            {{ props.errorDescription }}
        </p>
    </div>
</template>

<script>
//import axios from "@axios";

export default {

}
</script>


<style lang="scss">
@media print {
    .v-application {
        background: none !important;
    }

    @page {
        margin: 0;
        size: auto;
    }

    .layout-page-content,
    .v-row,
    .v-col-md-9 {
        padding: 0;
        margin: 0;
    }

    .product-buy-now {
        display: none;
    }

    .v-navigation-drawer,
    .layout-vertical-nav,
    .app-customizer-toggler,
    .layout-footer,
    .layout-navbar,
    .layout-navbar-and-nav-container {
        display: none;
    }

    .v-card {
        box-shadow: none !important;

        .print-row {
            flex-direction: row !important;
        }
    }

    .layout-content-wrapper {
        padding-inline-start: 0 !important;
    }
}

/* width */
::-webkit-scrollbar {
    block-size: 10px;
    inline-size: 10px;
}

/* Track */
::-webkit-scrollbar-track {
    border-radius: 10px;
    box-shadow: inset 0 0 5px grey;
}

/* Handle */
::-webkit-scrollbar-thumb {
    border-radius: 10px;
    background: mediumpurple;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: rgba(147, 112, 219, 60%);
}
</style>
<route lang="yaml">
meta:
  layout: blank
</route>