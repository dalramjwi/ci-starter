document.addEventListener("DOMContentLoaded", function () {
  let currentPage = window.currentPage;
  let currentLimit = window.currentLimit;
  const totalCount = window.totalCount;

  const postList = document.getElementById("post_list");
  const pageOption = document.getElementById("page_option");
  const pagination = document.getElementById("pagination");

  function renderPagination(totalPages, currentPage) {
    const blockSize = 10;
    const blockStart =
      Math.floor((currentPage - 1) / blockSize) * blockSize + 1;
    let blockEnd = blockStart + blockSize - 1;
    if (blockEnd > totalPages) blockEnd = totalPages;

    pagination.innerHTML = "";

    if (blockStart > 1) {
      const prevBlockBtn = document.createElement("button");
      prevBlockBtn.textContent = "<<";
      prevBlockBtn.addEventListener("click", () =>
        fetchPosts(blockStart - 1, currentLimit)
      );
      pagination.appendChild(prevBlockBtn);
    }

    for (let i = blockStart; i <= blockEnd; i++) {
      const btn = document.createElement("button");
      btn.textContent = i;
      if (i === currentPage) btn.disabled = true;
      btn.addEventListener("click", () => fetchPosts(i, currentLimit));
      pagination.appendChild(btn);
    }

    if (blockEnd < totalPages) {
      const nextBlockBtn = document.createElement("button");
      nextBlockBtn.textContent = ">>";
      nextBlockBtn.addEventListener("click", () =>
        fetchPosts(blockEnd + 1, currentLimit)
      );
      pagination.appendChild(nextBlockBtn);
    }
  }

  function fetchPosts(page, limit) {
    const bodyData = {
      page_option: limit,
      page: page,
    };

    if (window.keyword) {
      bodyData.keyword = window.keyword;
    }

    fetch(window.fetchPostsUrl, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(bodyData),
    })
      .then((response) => response.json())
      .then((data) => {
        postList.innerHTML = data.html;
        renderPagination(data.total_pages, data.current_page);
        currentPage = data.current_page;
        currentLimit = limit;
      })
      .catch(() => alert("게시글을 불러오는 중 오류가 발생했습니다."));
  }

  pageOption.addEventListener("change", function () {
    currentLimit = parseInt(this.value);
    fetchPosts(1, currentLimit);
  });

  const totalPages = Math.ceil(totalCount / currentLimit);
  renderPagination(totalPages, currentPage);
});
