<html>
<head>
  <style>
    body{
      font-family: sans-serif;
    }
    @page {
      margin: 160px 50px;
    }
    header { position: fixed;
      left: 0px;
      top: -160px;
      right: 0px;
      height: 100px;
      background-color: #ddd;
      text-align: center;
    }
    header h1{
      margin: 10px 0;
    }
    header h2{
      margin: 0 0 10px 0;
    }
    footer {
      position: fixed;
      left: 0px;
      bottom: -50px;
      right: 0px;
      height: 40px;
      border-bottom: 2px solid #ddd;
    }
    footer .page:after {
      content: counter(page);
    }
    footer table {
      width: 100%;
    }
    footer p {
      text-align: right;
    }
    footer .izq {
      text-align: left;
    }

/*
  https://www.joomlathat.com/support/invoice-manager/general/invoice-page-num-and-total-pages-for-pdf-version


*/
#pagenum:before { content: counter(page); }
/* CSS stuff below wouldn't work at all */
#pagetotal:before { content: counter(pages); }

#pagetotal:after { content: counter(pages); }


  </style>
<body>
  <header>
    <h1 id="pagenum"> Document Header</h1>
    <h2 id="pagetotal"> aBillander.com</h2>
  </header>
  <footer>
    <table>
      <tr>
        <td>
            <p class="izq">
              aBillander.com
            </p>
        </td>
        <td>
          <p class="page">
            Page
          </p>
        </td>
      </tr>
    </table>
  </footer>
  <div id="content">
    <p>
      Lorem ipsum dolor sit...
    </p><p>
    Vestibulum pharetra fermentum fringilla...
    </p>
    <p style="page-break-before: always;">
    Page break (anytime...)</p>
    </p><p>
    Praesent pharetra enim sit amet...
    </p>
  </div>
</body>
</html>