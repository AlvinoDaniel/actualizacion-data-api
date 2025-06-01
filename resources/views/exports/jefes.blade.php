<table>
    <thead>
        <tr>
           <th width="20"  align="center"  style="background-color: #e1e2ff;" >CEDULA IDENTIDAD</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >NOMBRES Y APELLIDOS</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >SEXO</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >TIPO PERSONAL</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >CARGO JEFE</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >COD NUCLEO</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >CORREO</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >TELEFONO</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >AREA TRABAJO</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >PANTALON</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >CAMISA</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >ZAPATO</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >TIPO ZAPATO</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >PRENDA EXTRA</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >COD UNIDAD ADMIN</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >UNIDAD ADMIN</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >COD UNIDAD EJEC</th>
            <th  width="50" align="center"  style="background-color: #e1e2ff;" >UNIDAD EJEC</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($report as $item)
            <tr>
           <td align="center" >{{ $item->cedula_identidad }}</td>
            <td align="center" >{{ $item->nombres_apellidos }}</td>
            <td align="center" >{{ $item->sexo }}</td>
            <td align="center" >{{ $item->tipo_personal }}</td>
            <td>{{ $item->cargo_jefe }}</td>
            <td>{{ $item->cod_nucleo }}</td>
            <td>{{ $item->correo }}</td>
            <td>{{ $item->telefono }}</td>
            <td>{{ $item->area_trabajo }}</td>
            <td>{{ $item->pantalon }}</td>
            <td>{{ $item->camisa }}</td>
            <td>{{ $item->zapato }}</td>
            <td>{{ $item->tipo_calzado_descripcion }}</td>
            <td>{{ $item->tipo_prenda_descripcion }}</td>
            <td>{{ $item->codigo_unidad_admin }}</td>
            <td>{{ $item->descripcion_unidad_admin }}</td>
            <td>{{ $item->codigo_unidad_ejec }}</td>
            <td>{{ $item->descripcion_unidad_ejec }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


