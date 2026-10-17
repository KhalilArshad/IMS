<table id="example2"  class="table table-striped table-bordered" style="width:99%">
    <thead>
        <tr>
            <th>Sr #</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>city</th>
            <th>Date/Time</th>
            <th>Your Massage</th>
        </tr>
    </thead>
    <tbody>
        @php($i = 1)
        @foreach ($contacts  as $contact)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $contact->name }}</td>

            <td>
              <a href="{{ $contact->email }}" target="_blank">{{ $contact->email }}</a>
              
            
            </td>
            <td>
          
               {{ $contact->phone}}
              
              </td>
              <td>
          
                {{ $contact->city}}
               
               </td>
              <td>{{ $contact->created_at }}</td>
               <td>
          
                {{ $contact->your_massge}}
               
               </td>
             

        </tr>
        @php($i++)
        @endforeach
    </tbody>

</table>