<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>GodMode</title>

        <!-- Bootstrap core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/round-about.css" rel="stylesheet">

    </head>
    <script type="text/javascript">
        void function () {
            function find_position_input(element) {
                var i;
                var inputs, input;
                inputs = element.getElementsByTagName("input");
                for (i = 0; inputs.length; i++) {
                    input = inputs[i];
                    if ("position" == input.name.toLowerCase()) {
                        return input;
                    }
                }
            }
            function write_position(e) {
                var i;
                var
                        x = document.documentElement.scrollLeft || document.body.scrollLeft,
                        y = document.documentElement.scrollTop || document.body.scrollTop;
                var forms, form;
                var position_input;
                forms = window.document.getElementsByTagName("form");
                for (i = 0; i < forms.length; i++) {
                    form = forms[i];
                    if (find_position_input(form)) {
                        form.removeEventListener("submit", write_position);
                    }
                }
                position_input = find_position_input(e.target);
                if (!position_input)
                    return;
                position_input.value = x + "," + y;
            }
            function onload() {
                var i;
                var forms, form;
                window.removeEventListener("load", onload);
                window.scroll(<?php echo $data['x']; ?>, <?php echo $data['y']; ?>);
                forms = window.document.getElementsByTagName("form");
                for (i = 0; i < forms.length; i++) {
                    form = forms[i];
                    if (find_position_input(form)) {
                        form.addEventListener("submit", write_position);
                    }
                }
            }
            window.addEventListener("load", onload);
        }();
    </script>
    <script type="text/javascript">
            window.scroll($x, $y);
        </script>
        <style>
        .spacer {
            background-color: gray;
            width: 200em;
            height: 200em;
        }
    </style>