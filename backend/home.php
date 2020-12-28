<?php

include "conn.php";

function findCustomerName($id, $conn)
{
    $findCusQuery = "SELECT * FROM customer WHERE id = '$id'";
    $resultFindCus = mysqli_query($conn, $findCusQuery);
    if (mysqli_num_rows($resultFindCus) > 0) {
        return mysqli_fetch_assoc($resultFindCus)['name'];
    }
    else{
        return '';
    }
}


function findBrand($id, $conn){
    $findQuery = "SELECT * FROM brand WHERE id = '$id'";
    $resultFind = mysqli_query($conn, $findQuery);
    if (mysqli_num_rows($resultFind) > 0) {
        return mysqli_fetch_assoc($resultFind)['name'];
    }
    else{
        return '';
    }
}


if (isset($_POST['readOrderData'])) {
    $getOrderQuery = "SELECT * FROM orders";
    $resultGetOrderData = mysqli_query($conn, $getOrderQuery);

    if (mysqli_num_rows($resultGetOrderData) > 0) {
        $data = '
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Khách hàng</th>
                    <th>Ngày</th>
                    <th>Trạng thái</th>
                    <th>Số lượng</th>
                    <th>Chi tiết</th>
                    <th>Cập nhật</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
        ';
        while ($record = mysqli_fetch_assoc($resultGetOrderData)) {
            $data .= '
            <tr>
            <td>' . $record['id'] . '</td>
            <td>' . findCustomerName($record['id'], $conn) . '</td>
            <td>' . $record['date'] . '</td>
            <td>' . $record['status'] . '</td>
            <td>' . $record['quantity'] . '</td>
            <td><button onclick="detailOrder('.$record['id'].');">Chi tiết</button></td>
            <td><button onclick="editOrder('.$record['id'].');">Cập Nhật</button></td>
            <td><button onclick="deleteOrder('.$record['id'].')";>Xóa</button></td>
            </tr>
            ';
        }
        $data .= '
            </tbody>
        </table>
        ';
        echo $data;
    } else {
        echo 'fail';
    }
}


if (isset($_POST['detailOrder'])){
    $id = $_POST['id'];
    $getDetailOrderQuery = "SELECT * FROM detail_order WHERE order_id = '$id'";
    $resultGetDetailOrderData = mysqli_query($conn, $getDetailOrderQuery);

    if (mysqli_num_rows($resultGetDetailOrderData) > 0) {
        $data = '
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá tiền</th>
                    <th>Thành Tiền</th>
                    <th>Cập nhật</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
        ';
        while ($record = mysqli_fetch_assoc($resultGetDetailOrderData)) {
            $data .= '
            <tr>
            <td>' . $record['number'] . '</td>
            <td>' . findBrand($record['brand_id'], $conn) . '</td>
            <td>' . $record['quantity'] . '</td>
            <td>' . $record['price'] . '</td>
            <td>' . $record['total_price'] . '</td>
            <td><button onclick="editDetailOrder('.$record['number'].');">Cập Nhật</button></td>
            <td><button onclick="deleteDetailOrder('.$record['number'].')";>Xóa</button></td>
            </tr>
            ';
        }
        $data .= '
            </tbody>
        </table>
        ';
        echo $data;
    } else {
        echo 'fail';
    }
}