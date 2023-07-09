<script>
  document.addEventListener('DOMContentLoaded', () => {
    alertify.error("Sorry. Journal was not found. Perhaps it was");
    // Remove the search params
    const url = new URL(window.location.href);
    console.log({
      url
    })
    const params = url.searchParams;
    params.delete("type");
    params.delete("journal_id")
    url.search = params;
    window.history.replaceState(null, null, url.toString());
  })
</script>