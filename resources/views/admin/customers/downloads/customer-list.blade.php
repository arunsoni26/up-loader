@php
    set_time_limit(4000); 
    header('Set-Cookie: fileDownload=true; path=/');
    header('Cache-Control: max-age=60, must-revalidate');
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=customers-list-".time().".xls");
    header("Pragma: no-cache");
    header("Expires: 0"); 
@endphp

@if(!empty($customers) && count($customers) > 0)
    <table id="example" class="table table-striped table-bordered" style="width:100%" border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>GST Name</th>
                <th>Father's Name</th>
                <th>PAN</th>
                <th>Email</th>
                <th>Mobile Number</th>
                <th>Status</th>
                <th>Code</th>
                <th>Group</th>
                <th>GST</th>
                <th>Aadhar</th>
                <th>Date of Birth</th>
                <th>Address</th>
                <th>Verified Years</th>
            </tr>
        </thead>
        <tbody>
            @php $i=1; @endphp
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->gst_name }}</td>
                    <td>{{ $customer->father_name }}</td>
                    <td>{{ $customer->pan }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->mobile_no }}</td>
                    <td>{{ strtoupper($customer->client_type_status) }}</td>
                    <td>{{ $customer->code }}</td>
                    <td>{{ $customer->group->name }}</td> {{-- replace with relation if needed --}}
                    <td>{{ $customer->gst }}</td>
                    <td>{{ $customer->aadhar }}</td>
                    <td>{{ $customer->dob ? \Carbon\Carbon::parse($customer->dob)->format('d-m-Y') : '' }}</td>
                    <td>{{ $customer->address }}</td>
                    <td>
                        @if($customer->verifiedYears->count())
                            @foreach($gstYears as $year)
                                @php
                                    $match = $customer->verifiedYears->firstWhere('gst_year_id', $year->id);
                                @endphp
                                {{ $year->label }} - 
                                {{ $match ? ($match->is_verify ? 'Verified' : 'Pending') : 'Pending' }}
                                <br>
                            @endforeach
                        @else
                            @foreach($gstYears as $year)
                                {{ $year->label }} - Pending<br>
                            @endforeach
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
