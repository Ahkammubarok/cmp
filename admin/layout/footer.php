<footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.2.0
    </div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="/cmp/admin/assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="/cmp/admin/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="/cmp/admin/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="/cmp/admin/assets/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="/cmp/admin/assets/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="/cmp/admin/assets/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="/cmp/admin/assets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="/cmp/admin/assets/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="/cmp/admin/assets/plugins/moment/moment.min.js"></script>
<script src="/cmp/admin/assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="/cmp/admin/assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="/cmp/admin/assets/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="/cmp/admin/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="/cmp/admin/assets/dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="/cmp/admin/assets/dist/js/pages/dashboard.js"></script>
<!-- DataTables  & Plugins -->
<script src="/cmp/admin/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/cmp/admin/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/cmp/admin/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/cmp/admin/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/cmp/admin/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/cmp/admin/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/cmp/admin/assets/plugins/jszip/jszip.min.js"></script>
<script src="/cmp/admin/assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="/cmp/admin/assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="/cmp/admin/assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/cmp/admin/assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/cmp/admin/assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- datatable client side -->
<script>
    $(function() {
        $('#example2').DataTable({

        });
    });
</script>
<!-- datatable serverside -->
<script>
   $('#serverside').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: "http://localhost/cmp/admin/_mahasiswa/mahasiswa-serverside.php?action=table_data",
        type: "POST",
        contentType: "application/x-www-form-urlencoded", // Pastikan format dikirim dengan benar
        data: { action: "fetch" }, // Kirim parameter action dengan benar
    },
    columns: [
        { data: "no" },
        { data: "nama" },
        { data: "prodi" },
        { data: "jk" },
        { data: "telepon" },
        { data: "alamat" },
        { data: "email" },
        { data: "aksi", orderable: false }
    ]
});

    

    //text area sejarah
    $(function () {
    // Summernote
    $('#alamat').summernote()

    // CodeMirror
    CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
        mode: "htmlmixed",
        theme: "monokai"
    });
    })

    //text area visi 
    $(function () {
    // Summernote
    $('#isi').summernote()

    // CodeMirror
    CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
        mode: "htmlmixed",
        theme: "monokai"
    });
    })

    // text area misi
    $(function () {
    // Summernote
    $('#isi_misi').summernote()

    // CodeMirror
    CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
        mode: "htmlmixed",
        theme: "monokai"
    });
    })
</script>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin logout?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="/cmp/admin/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
</div>
</body>

</html>
