<?php

namespace App\Console\Commands;

use App\Jobs\SendMailJob;
use App\Jobs\SMSJob;
use App\Models\CertificateForm;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class OrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'marked cancelled or remind user about their order pickup date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        activity()->disableLogging();
        // marked the order and its certficate forms when the order (pickup mode) is not received within 3 days.
        Order::where('pickup_date', '<=',Carbon::now()->subDays(3)->toDateTimeString())
            ->where('application_status', 'Approved')
            ->where('pick_up_type', 'Pickup')
            ->where('order_status', 'Waiting')
            ->each(function ($order) {
                $ids = $order->certificateForms()->pluck('id');
                foreach($ids as $id) {
                    CertificateForm::where('id', $id)
                        ->update(['status' => 'Cancelled']);
                }
                $order->application_status = "Cancelled";
                $order->order_status = "Pending";
                $order->save();

                $subject = 'Certificate Order Cancellation Notification!';
                $label1 = 'Please take note that your order #'.$order->id.' has been cancelled by the system since you did not received the specified order within 3 days after the specified pickup date.';
                $label2 = 'Thankyou for using this application.';
                $message = $label1.$label2;
                dispatch(new SMSJob($order->phone_no, $message));
                dispatch(new SendMailJob($order->email, $subject, $message));
            });

        // remind user about their order when their orderand their pickupdate is today
        Order::where('pickup_date', Carbon::today())
            ->where('application_status', 'Approved')
            ->each(function ($order) {
                $subject = 'Certificate pickup date reminder!';
                $label1 = 'Please take note that your order #'.$order->id.' assigned pickup date is today. Please go to the barangay office to pickup your requested certificate (If pickup) or Expect the biker to pickup the delivery item (If delivery and booked by the biker). Take note of your order ID and prepare credentials.';
                $label2 = 'Be aware that if you did not received (Pickup) or it did not booked by the biker, the order within 3 days after the assigned pickup date, your order will be marked as cancelled. Thankyou for using this application.';
                $message = $label1.$label2;
                dispatch(new SMSJob($order->phone_no, $message));
                dispatch(new SendMailJob($order->email, $subject, $message));

                if ($order->delivered_by != NULL) {
                    $subject = 'Certificate Delivery pickup date reminder!';
                    $label1 = 'Please take note that your booked order #'.$order->id.' assigned pickup date is today. Please go to the barangay office to pickup the specified order to deliver.';
                    $label2 = 'Be aware that if you did not deliver this item 3 days after the assigned pickup date, your order will be marked as cancelled. Thankyou for using this application.';
                    $message = $label1.$label2;
                    dispatch(new SMSJob($order->biker->phone_no, $message));
                    dispatch(new SendMailJob($order->biker->email, $subject, $message));
                }
            });

        // marked the order (Delivery) as cancelled when the order does not booked by the biker
        Order::where('pickup_date', '<=', Carbon::now()->subDays(3)->toDateTimeString())
            ->where('application_status', 'Approved')
            ->where('pick_up_type', 'Delivery')
            ->where('order_status', 'Waiting')
            ->where('delivered_by', NULL)
            ->each(function ($order) {
                $ids = $order->certificateForms()->pluck('id');
                foreach($ids as $id) {
                    CertificateForm::where('id', $id)
                        ->update(['status' => 'Cancelled']);
                }
                $order->application_status = "Cancelled";
                $order->order_status = "Pending";
                $order->save();

                $subject = 'Certificate Order Cancellation Notification!';
                $label1 = 'Please take note that your order #'.$order->id.' has been cancelled by the system since the order has not been picked by the biker to deliver within 3 days after the specified pickup date.';
                $label2 = 'Thankyou for using this application.';
                $message = $label1.$label2;
                dispatch(new SMSJob($order->phone_no, $message));
                dispatch(new SendMailJob($order->email, $subject, $message));
            });

        Order::where('pickup_date', '<=', Carbon::now()->subDays(3)->toDateTimeString())
            ->where('application_status', 'Approved')
            ->where('pick_up_type', 'Delivery')
            ->where('order_status', 'Accepted')
            ->each(function ($order) {
                $ids = $order->certificateForms()->pluck('id');
                foreach($ids as $id) {
                    CertificateForm::where('id', $id)
                        ->update(['status' => 'Cancelled']);
                }
                $order->application_status = "Cancelled";
                $order->order_status = "Pending";
                $order->save();

                $subject = 'Certificate Order Cancellation Notification!';
                $label1 = 'Please take note that your order #'.$order->id.' has been cancelled by the system since the designated biker has not been picked your order to deliver within 3 days after the specified pickup date.';
                $label2 = 'Thankyou for using this application.';
                $message = $label1.$label2;
                dispatch(new SMSJob($order->phone_no, $message));
                dispatch(new SendMailJob($order->email, $subject, $message));

                $subject = 'Certificate Delivery Cancellation Notification!';
                $label1 = 'Please take note that your booked order #'.$order->id.' has been cancelled by the system since you did not receive pickup the order to deliver within 3 days after the specified pickup date.';
                $label2 = 'Thankyou for using this application.';
                $message = $label1.$label2;
                dispatch(new SMSJob($order->biker->phone_no, $message));
                dispatch(new SendMailJob($order->biker->email, $subject, $message));
            });

    }
}
