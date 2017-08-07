<?php get_header(); ?>

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Label drucken
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">

      <div class="col-sm-12">
      	<div class="box">
          <form role="form" method="get" action="/print" target="_blank">
          	<div class="box-header with-border">
             		<h3 class="box-title">Auswahl</h3>
                  <div class="box-tools pull-right">
                    	<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip">
                      <i class="fa fa-minus"></i></button>
                  </div>
              </div>
			  <div class="box-body">
				<div class="row">
				<div class="col-sm-4">
          <div class="form-group">
            <label>Artikelnummer</label>
            <input type="text" class="form-control" name="range" placeholder="von-bis, einzeln">
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <label>Labelversatz</label>
            <input type="text" class="form-control" name="offset" placeholder="0">
          </div>
        </div>
				</div>
				<!-- /.row -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <div class="pull-right">
                    <button type="submit" class="btn btn-default btn-flat">Drucken</button>
                  </div>
                </div>

                <!-- /.box-footer-->
              </form>
            </div>
            <!-- /.box -->

      </div><!-- /.col -->

    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->

<?php get_footer(); ?>

<script>
	$(function () {
		/* Navigationsmenü */
    	$("ul.sidebar-menu li#label").addClass("active");
	});
</script>
