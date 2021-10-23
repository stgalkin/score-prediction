<div class="col-lg-4">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col" colspan="2">{{$week}} Week Predictions of Championship</th>
        </tr>
        </thead>
        <tbody>
        @foreach($predictions as $name => $prediction)
            <tr>
                <td>{{$name}}</td>
                <td class="text-right">%{{$prediction}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
