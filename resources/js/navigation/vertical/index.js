export default [

  // {
  //   title: 'availability copy',
  //   to: { name: 'availability copy' },
  //   icon: { icon: 'tabler-smart-home', style: 'color:white' },
  //
  // },


  // {
  //   title: 'indexed_db',
  //   to: { name: 'indexed_db' },
  //   icon: { icon: 'mdi-chart-bubble', style: 'color:white' },

  // },
  // {
  //   title: 'ErrorHeaders',
  //   to: { name: 'ErrorHeaders' },
  //   icon: { icon: 'tabler-smart-home', style: 'color:white' },
  //
  // },
  {
    title: 'Dashboard',
    to: { name: 'index' },
    icon: { icon: 'tabler-smart-home', style: 'color:white' },
    action: 'view',
    subject: 'home',
  },
  {
    path: 'Setup',
    title: 'Setup',
    icon: { icon: 'mdi-file-chart', style: 'color:white' },
    children: [


      {
        title: 'General setup',
        to: { name: 'setup' },
        icon: { icon: 'mdi-home-floor-2', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'setup',
      },

      {

        title: 'Payment Type',
        to: { name: 'PaymentType' },
        icon: { icon: 'mdi-sitemap', style: 'font-size: 20px;margin-left:20px;color:white' },
      },
      {
        title: 'users',
        to: { name: 'AuthRegist-users' },
        icon: { icon: 'mdi-account-group', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'view',
        subject: 'user',
      },
      {
        title: 'Reservation statuses',
        to: { name: 'reservation-statuses' },
        icon: { icon: 'mdi-star', style: 'font-size: 20px;margin-left:20px;color:white' },

      },
      {
        title: 'Room statuses',
        to: { name: 'room-statuses' },
        icon: { icon: 'mdi-star', style: 'font-size: 20px;margin-left:20px;color:white' },

      },
      {
        title: 'Rate Code',
        to: { name: 'rate-code' },
        icon: { icon: 'mdi-star', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'view',
        subject: 'rate code',

      },
      {
        title: 'Extras',
        to: { name: 'extras' },
        icon: { icon: 'mdi-star', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'view',
        subject: 'extras',

      },
      {
        path: 'Meals',
        title: 'Meals',
        icon: { icon: 'mdi-sofa', style: 'font-size: 20px;margin-left:20px;color:white' },
        children: [
          {
            path: 'Meals',
            title: 'Meals',
            to: { name: 'Meals-meals' },
            icon: { icon: 'mdi-sofa', style: 'font-size: 20px;margin-left:20px;color:white' },
          },
          {
            title: 'Meal Package',
            to: { name: 'Meals-mealPackage' },
            icon: { icon: 'mdi-sofa', style: 'font-size: 20px;margin-left:20px;color:white' },
          },
        ],
      },
      {
        title: 'Rooms',
        icon: { icon: 'mdi-sofa', style: 'font-size: 20px;margin-left:20px;color:white' },
        children: [
          {
            title: 'Room',
            to: { name: 'rooms-rooms' },
            icon: { icon: 'mdi-sofa', style: 'font-size: 20px;margin-left:20px;color:white' },
            action: 'view',
            subject: 'room',
          },
          {
            title: 'Room Type',
            to: { name: 'rooms-room_type' },
            icon: { icon: 'mdi-sitemap', style: 'font-size: 20px;margin-left:20px;color:white' },
            action: 'view',
            subject: 'roomtype',
          },
          {
            title: 'Floors',
            to: { name: 'rooms-floors' },
            icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
            action: 'view',
            subject: 'floor',
          },
          {
            title: 'Room View',
            to: { name: 'rooms-room_view' },
            icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
          },
          {
            title: 'Room Dummay',
            to: { name: 'rooms-Dummay' },
            icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
          },
          {
            title: 'Generate rooms',
            to: { name: 'generate-rooms' },
            icon: { icon: 'mdi-home-floor-2', style: 'font-size: 20px;margin-left:20px;color:white' },
            action: 'generate',
            subject: 'rooms',
          },
          {
            title: 'Copy floor',
            to: { name: 'copy-floor' },
            icon: { icon: 'mdi-home-floor-2', style: 'font-size: 20px;margin-left:20px;color:white' },
            action: 'copy',
            subject: 'floor',
          },
        ],
      },
      {
        title: 'perme & roles',
        icon: { icon: 'mdi-file-chart', style: 'font-size: 20px;margin-left:20px;color:white' },
        children: [
          {
            title: 'permession Data',
            to: { name: 'Permissions-Roles-Permissions' },
            icon: { icon: 'mdi-file-chart', style: 'font-size: 20px;margin-left:20px;color:white' },
          },

          {
            title: 'Roles',
            to: { name: 'Permissions-Roles-roles-page' },
            icon: { icon: 'mdi-chart-bubble', style: 'font-size: 20px;margin-left:20px;color:white' },
            action: 'view',
            subject: 'roles',
          },
          {
            title: 'Assign permession',
            to: { name: 'assign-permessions' },
            icon: { icon: 'mdi-dip-switch', style: 'font-size: 20px;margin-left:20px;color:white' },
            action: 'view',
            subject: 'assign permissions',
          },
        ],
      },
      // {
      //   title: 'Plans - Feature',
      //   icon: { icon: 'mdi-file-chart', style: 'font-size: 20px;margin-left:20px;color:white' },
      //   children: [
      //     {
      //       title: 'Plan',
      //       to: { name: 'Plans-Features-plan' },
      //       icon: { icon: 'mdi-material-design', style: 'font-size: 20px;margin-left:20px;color:white' },
      //       action: 'view',
      //       subject: 'plan',
      //     },
      //     {
      //       title: 'Feature',
      //       to: { name: 'Plans-Features-feature' },
      //       icon: { icon: 'mdi-feature-search', style: 'font-size: 20px;margin-left:20px;color:white' },
      //       action: 'view',
      //       subject: 'feature',
      //     },
      //   ],
      // },
      {
        title: 'Taxs Ledgers',
        icon: { icon: 'mdi-file-chart', style: 'font-size: 20px;margin-left:20px;color:white' },
        children: [
          {
            title: 'tax',
            to: { name: 'tax' },
            icon: { icon: 'mdi-abacus', style: 'font-size: 20px;margin-left:20px;color:white' },
            action: 'view',
            subject: 'tax',
          },
          {
            title: 'ledger',
            to: { name: 'ledger' },
            icon: { icon: 'mdi-abacus', style: 'font-size: 20px;margin-left:20px;color:white' },
            action: 'view',
            subject: 'ledger',
          },
        ],
      },
      {
        title: 'Segment-Source',
        icon: { icon: 'mdi-file-chart', style: 'font-size: 20px;margin-left:20px;color:white' },
        children: [
          {
            title: 'Segment',
            to: { name: 'Segment-Source-Segment' },
            icon: { icon: 'mdi-segment', style: 'font-size: 20px;margin-left:20px;color:white' },
            action: 'view',
            subject: 'market segment',
          },
          {
            title: 'sourcebusiness',
            to: { name: 'Segment-Source-sourcebusiness' },
            icon: { icon: 'mdi-chart-timeline-variant', style: 'font-size: 20px;margin-left:20px;color:white' },
            action: 'view',
            subject: 'source of business',
          },
        ],
      },
      {
        title: 'Revenue budget',
        to: { name: 'revenue-budget' },
        icon: { icon: 'mdi-cash', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'view',
        subject: 'revenue budget',
      },
      {
        title: 'Ledger expenses',
        to: { name: 'expenses' },
        icon: { icon: 'mdi-cash', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'view',
        subject: 'ledger expenses',
      },
      {
        title: 'Notifications',
        to: { name: 'All_Notification' },
        icon: { icon: 'mdi-cash', style: 'font-size: 20px;margin-left:20px;color:white' },

      },

    ],
  },
  {

    title: 'Reservation',
    icon: { icon: 'mdi-ticket', style: 'color:white' },
    children: [

      {
        title: 'Availability',
        to: { name: 'availability' },
        icon: { icon: 'mdi-cash', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'view',
        subject: 'availability',
      },
      {
        title: 'Search reservation',
        to: { name: 'reservations' },
        icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'search-reservation',
      },
      {
        title: 'Group reservation',
        to: { name: 'group-reservation' },
        icon: { icon: 'mdi-ticket', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'group reservation',
      },
      {
        title: 'Monthly/Yearly reservation',
        to: { name: 'month-year-reservation' },
        icon: { icon: 'mdi-ticket', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'month year reservation',
      },
      {
        title: 'Create reservation',
        to: { name: 'create-reservation' },
        icon: { icon: 'mdi-chart-tree', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'create-reservation',
        meta: {
          layout: 'blank',
        },

      },
      {
        title: 'Blocked rooms',
        to: { name: 'block-rooms' },
        icon: { icon: 'mdi-star', style: 'font-size: 20px;margin-left:20px;color:white' },

      },
      {
        title: 'profile',
        to: { name: 'profile-Profile' },
        icon: { icon: 'mdi-sofa', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'view',
        subject: 'profile',
      },

      {
        title: 'Char Sum Res',
        to: { name: 'SumRes' },
        icon: { icon: 'mdi-sitemap', style: 'font-size: 20px;margin-left:20px;color:white' },
      },
    ],
  },

  {
    title: 'Guests',
    icon: { icon: 'mdi-file-chart', style: 'color:white' },
    children: [

      {
        title: 'Cashier closer',
        to: { name: 'cashier-closer' },
        icon: { icon: 'mdi-abacus', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'cashier-closer',
      },
      {
        title: 'Guest',
        to: { name: 'filter-guest' },
        icon: { icon: 'mdi-abacus', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'guest-filter',
      },
      {
        title: 'guest filter',
        to: { name: 'guest_filter' },
        icon: { icon: 'mdi-abacus', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'guest-filter',
      },
      {
        title: 'in house guest',
        to: { name: 'in_house_guest' },
        icon: { icon: 'mdi-chart-bubble', style: 'font-size: 20px;margin-left:20px;color:white' },
      },
      {
        title: 'Checkout invoices',
        to: { name: 'checkout-invoices' },
        icon: { icon: 'mdi-hand', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'checkout-incoices',
      },
      {
        title: 'Rack',

        // target: '_blank',
        to: { name: 'Rack' },
        icon: { icon: 'mdi-chart-timeline-variant', style: 'font-size: 20px;margin-left:20px;color:white' },

      },
      {
        title: 'rooms  by  floor',
        to: { name: 'rooms_by_floor' },
        icon: { icon: 'mdi-material-design', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'rooms-by-floor',
      },
      {
        title: 'rooms  by Home',
        to: { name: 'rooms_by_floor2' },
        icon: { icon: 'mdi-material-design', style: 'font-size: 20px;margin-left:20px;color:white' },
      },

      {
        title: 'search guests',
        to: { name: 'Search_Guest' },
        icon: { icon: 'mdi-chart-bubble', style: 'font-size: 20px;margin-left:20px;color:white' },
      },
      {
        title: 'black List',
        to: { name: 'black_List' },
        icon: { icon: 'mdi-sitemap', style: 'font-size: 20px;margin-left:20px;color:white' },
      },
    ],
  },


  {

    title: 'House Keeping',
    icon: { icon: 'mdi-file-chart', style: 'color:white' },
    children: [

      {
        title: 'Show rooms status',
        to: { name: 'display-room-status' },
        icon: { icon: 'mdi-star', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'room-status',
      },



      {
        title: 'Change room status',
        to: { name: 'change-room-status' },
        icon: { icon: 'mdi-air-filter', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'edit',
        subject: 'change-room-status',
      },
      {
        title: 'Change room status V2',
        to: { name: 'change-room-status-v2' },
        icon: { icon: 'mdi-air-filter', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'edit',
        subject: 'change-room-status',
      },
      {
        title: 'maintenance',
        to: { name: 'maintenance' },
        icon: { icon: 'mdi-chart-tree', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'view',
        subject: 'maintenence',
      },




      {
        title: 'Oord Service',
        to: { name: 'OordService-OordServiceList' },
        icon: { icon: 'mdi-chart-bubble', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'oord',
      },
      {
        title: 'Lost And Found',
        to: { name: 'LostAndFound' },
        icon: { icon: 'mdi-dip-switch', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'lost-and-found',
      },

      {

        title: 'Discrepancy List',
        to: { name: 'discripence_list' },
        icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
      },

      {

        title: ' Room Discrepancy',
        to: { name: 'rooms-discrepancy' },
        icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
      },


    ],
  },





  // {
  //   title: 'All Reports',
  //   to: { name: 'AllReports' },
  //   icon: { icon: 'mdi-sitemap' },
  //   action: 'view',
  //   subject: 'Reports',
  // },


  // {
  //   title: 'Profit Loss',
  //   to: { name: 'profit_loss' },
  //   icon: { icon: 'mdi-chart-bubble' },
  // },
  // {
  //   title: 'Balance Sheet',
  //   to: { name: 'balance_sheet' },
  //   icon: { icon: 'mdi-chart-bubble' },
  // },
  // {
  //   title: 'black List',
  //   to: { name: 'black_List' },
  //   icon: { icon: 'mdi-sitemap' },
  // },
  // {
  //   title: 'Account Statment',
  //   to: { name: 'AccountStatment' },
  //   icon: { icon: 'mdi-sitemap' },
  // },
  // {
  //   title: 'Accounts Trail Balance',
  //   to: { name: 'AccountsTrailBalance' },
  //   icon: { icon: 'mdi-sitemap' },
  // },

  // {
  //   title: 'Reservation Confirmation',
  //   to: { name: 'Reservation_Confirmation' },
  //   icon: { icon: 'mdi-sitemap' },
  // },
  {
    title: 'sign up',
    to: { name: 'sign-up' },
    icon: { icon: 'mdi-dip-switch', style: 'font-size: 20px;color:white' },
    action: 'menu',
    subject: 'signup',
  },



  {

    title: 'Chart',
    icon: { icon: 'mdi-file-chart', style: 'color:white' },
    children: [

      {
        title: 'Daywise Occupancy',
        to: { name: 'charts-Daywise_Occupancy' },
        icon: { icon: 'mdi-chart-bubble', style: 'font-size: 20px;margin-left:20px;color:white' },
      },
      {
        title: 'Live Occupancy',
        to: { name: 'charts-Live_Occupancy' },
        icon: { icon: 'mdi-chart-bubble', style: 'font-size: 20px;margin-left:20px;color:white' },
      },

      {
        title: 'Monthwise revenue',
        to: { name: 'charts-monthwise_revenue' },
        icon: { icon: 'mdi-chart-bubble', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'view',
        subject: 'Monthwise-revenue',
      },
      {
        title: 'Monthwise occupancy',
        to: { name: 'charts-monthwise_occupancy' },

        icon: { icon: 'mdi-chart-bubble', style: 'font-size: 20px;margin-left:20px;color:white' },
      },
    ],
  },

  {

    title: 'Night audit',
    icon: { icon: 'mdi-file-chart', style: 'color:white' },
    children: [

      {
        title: 'Day Close',
        to: { name: 'wizard-checkout' },
        icon: { icon: 'mdi-sitemap', style: 'font-size: 20px;margin-left:20px;color:white' },
      },
      {
        title: 'Auto Post',
        to: { name: 'wizard-PreCharge' },
        icon: { icon: 'mdi-material-design', style: 'font-size: 20px;margin-left:20px;color:white' },
      },
    ],
  },

  {
    title: 'Tickets',
    icon: { icon: 'mdi-file-chart', style: 'color:white' },
    children: [
      {
        title: 'ticket',
        to: { name: 'ticket-ticket' },
        icon: { icon: 'mdi-percent-box', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'view',
        subject: 'tickets',
      },
      {
        title: 'create-ticket',
        to: { name: 'ticket-create-ticket' },
        icon: { icon: 'mdi-percent-box', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'view',
        subject: 'create-ticket',
      },
    ],
  },




  // {
  //
  //   title: 'Reports',
  //   icon: { icon: 'mdi-file-chart', style: 'color:white' },
  //   children: [
  //     {
  //       title: 'Report',
  //       to: { name: 'Reports-Reports' },
  //       icon: { icon: 'mdi-chart-timeline-variant', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'OO and oS',
  //       to: { name: 'Reports-OO_and_oS' },
  //       icon: { icon: 'mdi-chart-timeline-variant', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'cashier close',
  //       to: { name: 'Reports-cashier_close' },
  //       icon: { icon: 'mdi-chart-timeline-variant', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'cashier close summary',
  //       to: { name: 'Reports-cashier_close_summary' },
  //       icon: { icon: 'mdi-chart-timeline-variant', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Transactions Details',
  //       to: { name: 'Reports-Report_Transactions_Details' },
  //       icon: { icon: 'mdi-account-group', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Transactions Details user',
  //       to: { name: 'Reports-Report_Transactions_Details_users' },
  //       icon: { icon: 'mdi-account-group', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //
  //     {
  //       title: 'Transactions Taxes',
  //       to: { name: 'Reports-Transactions_tax' },
  //       icon: { icon: 'mdi-account-group', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Voided Transactions',
  //       to: { name: 'Reports-Report_Voided_Transactions' },
  //       icon: { icon: 'mdi-sitemap', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Room Change',
  //       to: { name: 'Reports-Report_Room_Change' },
  //       icon: { icon: 'mdi-sitemap', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Rate Change',
  //       to: { name: 'Reports-Report_Rate_Change' },
  //       icon: { icon: 'mdi-sitemap', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Guest Inhouse',
  //       to: { name: 'Reports-Report_Guest_inhouse' },
  //       icon: { icon: 'mdi-sitemap', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Actual Checked In',
  //       to: { name: 'Reports-Report_Actual' },
  //       icon: { icon: 'mdi-sitemap', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'departure Checked Out',
  //       to: { name: 'Reports-Report_departure' },
  //       icon: { icon: 'mdi-sitemap', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Reciept Voucher',
  //       to: { name: 'Reports-Reciept_Voucher' },
  //       icon: { icon: 'mdi-file-chart', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Payment Voucher',
  //       to: { name: 'Reports-Payment_Voucher' },
  //       icon: { icon: 'mdi-chart-tree', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Revenue By Dates',
  //       to: { name: 'Reports-Revenue_By_Dates' },
  //       icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'House Keeping',
  //       to: { name: 'Reports-House_Keeping' },
  //       icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Reservation Overview',
  //       to: { name: 'Reports-Reservation_Overview' },
  //       icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Current room',
  //       to: { name: 'Reports-Current_room' },
  //       icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Income Statement',
  //       to: { name: 'Reports-Income_Statement' },
  //       icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Income Statement MTD',
  //       to: { name: 'Reports-Income_Statement_MTD' },
  //       icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'High Balance',
  //       to: { name: 'Reports-High_Balance' },
  //       icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Daily Sales',
  //       to: { name: 'Reports-Daily_Sales' },
  //       icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Occupancy By Month',
  //       to: { name: 'Reports-Occupancy_By_Month' },
  //       icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Occupancy By Month Date',
  //       to: { name: 'Reports-Occupancy_By_Month_Date' },
  //       icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //
  //     {
  //       title: 'Transactions List view',
  //       to: { name: 'Reports-Transactions_List_view' },
  //       icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'voucher list',
  //       to: { name: 'Reports-Voucher_List' },
  //       icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Reservation By Corporate',
  //       to: { name: 'Reports-Reservation_By_Corporate' },
  //       icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Manager 1 Report',
  //       to: { name: 'Reports-Manager_1_Report' },
  //       icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Manager 3 Report',
  //       to: { name: 'Reports-Manager_3_Report' },
  //       icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Guest InHouse Meal Plan',
  //       to: { name: 'Reports-Guest_InHouse_MealPlan' },
  //       icon: { icon: 'mdi-floor-plan', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'History_Forcast',
  //       to: { name: 'Reports-History_Forcast' },
  //       icon: { icon: 'mdi-star', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Prodictivity By Country',
  //       to: { name: 'Reports-Prodictivity_By_Country' },
  //       icon: { icon: 'mdi-star', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Prodictivity By Company',
  //       to: { name: 'Reports-Prodictivity_By_Company' },
  //       icon: { icon: 'mdi-star', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Prodictivity By Segment',
  //       to: { name: 'Reports-Prodictivity_By_Segment' },
  //       icon: { icon: 'mdi-star', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Prodictivity By Source Bussiness',
  //       to: { name: 'Reports-Prodictivity_By_SourceBussiness' },
  //       icon: { icon: 'mdi-star', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //
  //
  //   ],
  //
  // },

  // {
  //   title: 'Extend Stay',
  //   to: { name: 'selectedFolio-Extend_Stay' },
  //   icon: { icon: 'mdi-star' },
  // },
  {
    title: 'Company',
    icon: { icon: 'mdi-sitemap', style: 'color:white' },
    children: [
      {
        title: 'Company profile',
        to: { name: 'Company-company_profile' },
        icon: { icon: 'mdi-segment', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'view',
        subject: 'company profile',
      },
      {
        title: 'AR Company statement',
        to: { name: 'Company-company_statement' },
        icon: { icon: 'mdi-chart-timeline-variant', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'view',
        subject: 'company statement',
      },
      {
        title: 'Receipt voucher',
        to: { name: 'Company-company-payment' },
        icon: { icon: 'mdi-chart-timeline-variant', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'recipt-voucher',
      },
      {
        title: 'Company debit',
        to: { name: 'Company-company-debit' },
        icon: { icon: 'mdi-chart-timeline-variant', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'company-depit',
      },
      {
        title: 'Company Credit Note',
        to: { name: 'Company-Company_Credit_Note' },
        icon: { icon: 'mdi-chart-timeline-variant', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'company-credit',
      },
      {
        title: 'adv Payment',
        to: { name: 'Company-Adv_Payment' },
        icon: { icon: 'mdi-chart-timeline-variant', style: 'font-size: 20px;margin-left:20px;color:white' },
        action: 'menu',
        subject: 'company-adv-payment',
      },

    ],
  },

  {
    title: 'Direct bill invoices',
    to: { name: 'direct-bill-invoices' },
    icon: { icon: 'mdi-cash', style: 'color:white' },
    action: 'view',
    subject: 'direct bill invoices',
  },
  {
    title: 'Reports',
    to: { name: 'all-reports' },
    icon: { icon: 'mdi-chart-timeline-variant', style: 'color:white' },
  },
  {
    title: 'Logs',
    to: { name: 'logs' },
    icon: { icon: 'mdi-star', style: 'color:white' },
    action: 'view',
    subject: 'logs',

  },
  // {
  //   title: 'Profit Loss',
  //   to: { name: 'profit_loss' },
  //   icon: { icon: 'mdi-chart-bubble' },
  // },
  // {
  //   title: 'Balance Sheet',
  //   to: { name: 'balance_sheet' },
  //   icon: { icon: 'mdi-chart-bubble' },
  // },

  // {
  //   title: 'Account Statment',
  //   to: { name: 'AccountStatment' },
  //   icon: { icon: 'mdi-sitemap' },
  // },
  // {
  //   title: 'Accounts Trail Balance',
  //   to: { name: 'AccountsTrailBalance' },
  //   icon: { icon: 'mdi-sitemap' },
  // },

  // {
  //   title: 'Chart of account',
  //   to: { name: 'chart-account' },
  //   icon: { icon: 'mdi-account', style: 'color:white' },
  //   action: 'view',
  //   subject: 'chart of account',
  // },

  // {
  //   title: 'API tester',
  //   to: { name: 'api-tester' },
  //   icon: { icon: 'mdi-star', style: 'color:white' },
  // },

  // {
  //   title: 'All Reports',
  //   to: { name: 'AllReports' },
  //   icon: { icon: 'mdi-sitemap' },
  //   action: 'view',
  //   subject: 'Reports',
  // },


  // {
  //   title: 'Reservation Confirmation',
  //   to: { name: 'Reservation_Confirmation' },
  //   icon: { icon: 'mdi-sitemap' },
  // },
  // {
  //   title: 'sign up',
  //   to: { name: 'sign-up' },
  //   icon: { icon: 'mdi-dip-switch', style: 'font-size: 20px;color:white' },
  //   action: 'menu',
  //   subject: 'signup',
  // },




  // {
  //
  //   title: 'voucher',
  //   icon: { icon: 'mdi-file-chart', style: 'color:white' },
  //   children: [
  //
  //     {
  //       title: 'Voucher',
  //       to: { name: 'voucher-Voucher' },
  //       icon: { icon: 'mdi-sitemap', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Journal Voucher',
  //       to: { name: 'voucher-JournalVoucher' },
  //       icon: { icon: 'mdi-chart-bubble', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'All Voucher',
  //       to: { name: 'voucher-AllVoucher' },
  //       icon: { icon: 'mdi-chart-timeline-variant', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     //
  //     // {
  //     //   title: 'fdf',
  //     //   to: { name: 'voucher-Voucher' },
  //     //   icon: { icon: 'mdi-account' },
  //     // },
  //     // {
  //     //   title: 'Voucher Print',
  //     //   to: { name: 'voucher-Voucher' },
  //     //   icon: { icon: 'mdi-chart-timeline-variant' },
  //     // },
  //     {
  //       title: 'Transaction Print',
  //       to: { name: 'voucher-Transaction_Print' },
  //       icon: { icon: 'mdi-percent-box', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //     {
  //       title: 'Print Journal Voucher',
  //       to: { name: 'voucher-Print_Journal_Voucher' },
  //       icon: { icon: 'mdi-chart-timeline-variant', style: 'font-size: 20px;margin-left:20px;color:white' },
  //     },
  //   ],
  // },



]
