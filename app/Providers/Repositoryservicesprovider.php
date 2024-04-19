<?php

namespace App\Providers;

use App\Repository\DBrepositoryplan;
use App\Repository\DBrepositoryfeature;
use App\Repository\DBrepositoryGeneral;
use Illuminate\Support\ServiceProvider;
use App\Repository\Rooms\DBrepositoryRoom;
use App\Repository\DBrepositoryTaskDetails;
use App\Repository\Rooms\DBrepositoryTower;
use App\Repository\DBrepositoryNotification;
use App\Repositoryinterface\Generalinterface;
use App\Repository\Rooms\DBrepositoryGardenView;
use App\Repository\Rooms\DBrepositoryRoomChange;
use App\Repository\Integration\DBrepositoryShomoos;
use App\Repository\Rooms\DBrepositoryDayCloseProces;
use App\Repositoryinterface\Repositoryplaninterface;
use App\Repositoryinterface\Repositoryfeatureinterface;
use App\Repository\profiles\Calculate\DBRepositoryBudget;
use App\Repositoryinterface\Integration\ShomoosInterface;
use App\Repositoryinterface\Rooms\RepositoryRoomInterface;
use App\Repository\Integration\DBrepositoryNTMPIntegration;
use App\Repositoryinterface\RepositoryTaskDetailsinterface;
use App\Repositoryinterface\Rooms\RepositoryTowerInterface;
use App\Repositoryinterface\RepositoryNotificationinterface;
use App\Repository\profiles\Calculate\DBRepositoryDirectBill;
use App\Repository\profiles\guestProfile\DBrepositoryInvoice;
use App\Repository\Integration\DBrepositoryShomoosIntegration;
use App\Repository\profiles\guestProfile\DBrepositoryExpenses;
use App\Repository\profiles\guestProfile\DBrepositoryPreCharge;
use App\Repositoryinterface\Rooms\RepositoryRoomChangeInterface;
use App\Repositoryinterface\Rooms\RepositoryViewGardenInterface;
use App\Repository\profiles\Calculate\DBRepositoryDayCloseRecord;
use App\Repository\profiles\guestProfile\DBrepositoryDiscrepancy;
use App\Repository\profiles\guestProfile\DBrepositoryGuestWindow;
use App\Repository\profiles\guestProfile\DBrepositoryChargeRouted;
use App\Repository\profiles\guestProfile\DBrepositoryGuestInhouse;
use App\Repository\profiles\guestProfile\DBrepositoryGuestPrpfile;
use App\Repositoryinterface\Integration\IntegrationForNTInterface;
use App\Repository\profiles\guestProfile\DBrepositoryExpensesLedger;
use App\Repositoryinterface\Integration\ShomoosIntegrationInterface;
use App\Repositoryinterface\Rooms\RepositoryDayCloseProcesInterface;
use App\Repository\profiles\Calculate\DBRepositoryJournalVoucherType;
use App\Repository\profiles\guestProfile\DBrepositoryChartOfAccounts;
use App\Repository\profiles\guestProfile\DBrepositoryExtraBedAndMeal;
use App\Repository\profiles\Calculate\DBRepositoryJournalVoucherMaster;
use App\Repository\profiles\guestProfile\DBrepositoryReservatsionChart;
use App\Repository\profiles\Calculate\DBRepositoryJournalVoucherDetails;
use App\Repositoryinterface\Profiles\Calculate\RepositoryBudgetInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryWindowInterface;
use App\Repository\profiles\guestProfile\DBrepositoryReservisionChartSummary;
use App\Repositoryinterface\Profiles\Calculate\RepositoryDirectBillInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryInvoiceInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryExpensesInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryPreChargeInterface;
use App\Repositoryinterface\Profiles\Calculate\RepositoryDayCloseRecordInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryDiscrepancyInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryChargeRoutedInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryGuestInhouseInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryGuestProfileInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryExpensesLedgerInterface;
use App\Repositoryinterface\Profiles\Calculate\RepositoryJournalVoucherTypeInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryChartOfAccountsInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryExtraBedAndMealInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryReservationChartInterface;
use App\Repositoryinterface\Profiles\Calculate\RepositoryJournalVoucherMasterInterface;
use App\Repositoryinterface\Profiles\Calculate\RepositoryJournalVoucherDetailsInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryReservisionChartSummaryInterface;

class Repositoryservicesprovider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(Repositoryplaninterface::class, DBrepositoryplan::class);
        $this->app->bind(Repositoryfeatureinterface::class, DBrepositoryfeature::class);
        $this->app->bind(RepositoryRoomInterface::class,DBrepositoryRoom::class);
        $this->app->bind(RepositoryGuestProfileInterface::class,DBrepositoryGuestPrpfile::class);
        $this->app->bind(RepositoryGuestInhouseInterface::class,DBrepositoryGuestInhouse::class);
        $this->app->bind(RepositoryChargeRoutedInterface::class,DBrepositoryChargeRouted::class);
        $this->app->bind(RepositoryDayCloseRecordInterface::class,DBRepositoryDayCloseRecord::class);
        $this->app->bind(RepositoryWindowInterface::class,DBrepositoryGuestWindow::class);
        $this->app->bind(RepositoryPreChargeInterface::class,DBrepositoryPreCharge::class);
        $this->app->bind(RepositoryRoomChangeInterface::class,DBrepositoryRoomChange::class);
        $this->app->bind(Generalinterface::class,DBrepositoryGeneral::class);
        $this->app->bind(RepositoryInvoiceInterface::class,DBrepositoryInvoice::class);
        $this->app->bind(RepositoryBudgetInterface::class,DBRepositoryBudget::class);
        $this->app->bind(RepositoryExpensesLedgerInterface::class,DBrepositoryExpensesLedger::class);
        $this->app->bind(RepositoryExpensesInterface::class,DBrepositoryExpenses::class);
        $this->app->bind(RepositoryChartOfAccountsInterface::class,DBrepositoryChartOfAccounts::class);
        $this->app->bind(RepositoryJournalVoucherTypeInterface::class,DBRepositoryJournalVoucherType::class);
        $this->app->bind(RepositoryJournalVoucherMasterInterface::class,DBRepositoryJournalVoucherMaster::class);
        $this->app->bind(RepositoryJournalVoucherDetailsInterface::class,DBRepositoryJournalVoucherDetails::class);
        $this->app->bind(RepositoryDayCloseProcesInterface::class,DBrepositoryDayCloseProces::class);
        $this->app->bind(RepositoryDiscrepancyInterface::class,DBrepositoryDiscrepancy::class);
        $this->app->bind(RepositoryDirectBillInterface::class,DBRepositoryDirectBill::class);
        $this->app->bind(RepositoryViewGardenInterface::class,DBrepositoryGardenView::class);
        $this->app->bind(RepositoryExtraBedAndMealInterface::class,DBrepositoryExtraBedAndMeal::class);
        $this->app->bind(IntegrationForNTInterface::class,DBrepositoryNTMPIntegration::class);
        $this->app->bind(RepositoryTaskDetailsinterface::class,DBrepositoryTaskDetails::class);
        $this->app->bind(RepositoryNotificationinterface::class,DBrepositoryNotification::class);
        $this->app->bind(RepositoryTowerInterface::class,DBrepositoryTower::class);
        $this->app->bind(RepositoryReservisionChartSummaryInterface::class,DBrepositoryReservisionChartSummary::class);
        $this->app->bind(RepositoryReservationChartInterface::class,DBrepositoryReservatsionChart::class);
        $this->app->bind(ShomoosInterface::class,DBrepositoryShomoos::class);
        $this->app->bind(ShomoosIntegrationInterface::class,DBrepositoryShomoosIntegration::class);
    }
}
