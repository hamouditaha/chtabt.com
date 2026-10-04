      </main>
    </div>
    <script>
      // confirm destructive actions
      document.querySelectorAll("[data-confirm]").forEach(function (el) {
        el.addEventListener("submit", function (event) {
          if (!confirm(el.getAttribute("data-confirm"))) event.preventDefault();
        });
      });
    </script>
  </body>
</html>
