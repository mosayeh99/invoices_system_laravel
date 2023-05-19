<x-mail::message>
    Dear Mr,

    There is new invoice created by employee
<h5>Name: {{$invoice->user->name}}</h5>
<h5>Email: {{$invoice->user->email}}</h5>
<x-mail::button :url="route('invoices.show', $invoice->id)">
View Invoice
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
