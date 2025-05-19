<table>
    <thead>
        <tr>
            <th width="20"  align="center"  style="background-color: #e1e2ff;" >CEDULA IDENTIDAD</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >NOMBRES Y APELLIDOS</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >TELEFONO</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >COD UNIDAD ADMIN</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >UNIDAD ADMIN</th>
        </tr>
    </thead>
    <tbody>
      @foreach ($report as $item)
        <tr>
            <td align="center" >{{ $item->cedula_identidad }}</td>
            <td align="center" >{{ $item->nombres_apellidos }}</td>
            <td>{{ $item->telefono }}</td>
            <td>{{ $item->codigo_unidad_admin }}</td>
            <td>{{ $item->descripcion_unidad_admin }}</td>
        </tr>
      @endforeach
    </tbody>
</table>


