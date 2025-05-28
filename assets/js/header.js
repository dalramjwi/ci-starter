document.addEventListener("DOMContentLoaded", function () {
  let currentPage = window.currentPage;
  let currentLimit = window.currentLimit;
  const totalCount = window.totalCount;
  const categoryListContent = window.categoryList;
  const fetchPostsUrl = window.fetchPostsUrl;

  let currentCategoryId = 0; // 기본은 전체

  const postList = document.getElementById("post_list");
  const pageOption = document.getElementById("page_option");
  const pagination = document.getElementById("pagination");
  const modal = document.getElementById("replyModal");
  const openBtn = document.querySelector(".reply-open-btn");
  const closeBtn = document.querySelector(".reply-close-btn");
  const commentForm = document.querySelector(".comment-form");
  const editButtons = document.querySelectorAll(".btn-edit");
  const categoryList = document.getElementById("category_list");

  // 모달 열기
  openBtn?.addEventListener("click", function () {
    modal.style.display = "block";
  });

  // 모달 닫기
  closeBtn?.addEventListener("click", function () {
    modal.style.display = "none";
  });

  // 바깥 클릭 시 닫기
  window.addEventListener("click", function (event) {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });

  // 댓글 비동기 작성 처리
  if (commentForm) {
    commentForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(this);
      const actionUrl = this.getAttribute("action");

      fetch(actionUrl, {
        method: "POST",
        body: formData,
      })
        .then((response) => response.text())
        .then((data) => {
          if (data.trim() === "ok") {
            location.reload();
          } else {
            alert("댓글 작성 실패: 서버 응답 이상\n" + data);
          }
        })
        .catch((error) => {
          console.error("댓글 작성 실패:", error);
          alert("댓글 작성 중 오류가 발생했습니다.");
        });
    });
  }

  // 댓글 수정 인라인 처리
  editButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();

      const li = this.closest("li");
      const contentSpan = li.querySelector(".comment-content");
      const originalContent = contentSpan.textContent.trim();

      if (li.querySelector("textarea")) return;

      // 기존 내용 숨김
      contentSpan.style.display = "none";

      // textarea 생성 + 기존 내용 넣기
      const textarea = document.createElement("textarea");
      textarea.classList.add("edit-textarea");
      textarea.value = originalContent;
      li.insertBefore(textarea, contentSpan.nextSibling);

      // 수정 완료 버튼 생성
      const saveBtn = document.createElement("button");
      saveBtn.textContent = "수정 완료";
      saveBtn.classList.add("btn-save");
      li.insertBefore(saveBtn, textarea.nextSibling);

      // 수정 취소 버튼 생성
      const cancelBtn = document.createElement("button");
      cancelBtn.textContent = "수정 취소";
      cancelBtn.classList.add("btn-cancel");
      li.insertBefore(cancelBtn, saveBtn.nextSibling);

      // 수정 완료 클릭 이벤트
      saveBtn.addEventListener("click", () => {
        const newContent = textarea.value.trim();

        if (newContent === "") {
          alert("댓글 내용을 입력하세요.");
          return;
        }

        if (newContent === originalContent) {
          alert("변경된 내용이 없습니다.");
          return;
        }

        const updateUrl = button.href.replace("edit_comment", "update_comment");

        fetch(updateUrl, {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "content=" + encodeURIComponent(newContent),
        })
          .then((response) => response.text())
          .then((data) => {
            if (data.trim() === "ok") {
              // 성공 시 댓글 내용 업데이트 (줄바꿈 처리)
              contentSpan.innerHTML = newContent.replace(/\n/g, "<br>");
              contentSpan.style.display = "inline";

              textarea.remove();
              saveBtn.remove();
              cancelBtn.remove();
            } else {
              alert("댓글 수정 실패: " + data);
            }
          })
          .catch(() => {
            alert("댓글 수정 중 오류가 발생했습니다.");
          });
      });

      // 수정 취소 클릭 이벤트
      cancelBtn.addEventListener("click", () => {
        textarea.remove();
        saveBtn.remove();
        cancelBtn.remove();
        contentSpan.style.display = "inline";
      });
    });
  });
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
      category_id: currentCategoryId,
    };

    if (window.keyword) {
      bodyData.keyword = window.keyword;
    }

    fetch(fetchPostsUrl, {
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

  // 카테고리 리스트 생성
  function renderCategoryList(categories) {
    categoryList.innerHTML = "";

    const allBtn = document.createElement("div");
    allBtn.className = "category_0";
    allBtn.textContent = "전체 게시판";
    allBtn.dataset.categoryId = 0;
    categoryList.appendChild(allBtn);

    categories.forEach((cat) => {
      const btn = document.createElement("div");
      btn.className = `category_btn${cat.category_id}`;
      btn.textContent = cat.name;
      btn.dataset.categoryId = cat.category_id;
      categoryList.appendChild(btn);
    });
  }

  // 카테고리 클릭 이벤트 처리
  categoryList.addEventListener("click", function (e) {
    if (e.target && e.target.dataset.categoryId !== undefined) {
      currentCategoryId = parseInt(e.target.dataset.categoryId);
      fetchPosts(1, currentLimit); // 선택된 카테고리로 게시글 다시 불러오기
    }
  });

  renderCategoryList(categoryListContent);

  pageOption.addEventListener("change", function () {
    currentLimit = parseInt(this.value);
    fetchPosts(1, currentLimit);
  });

  const totalPages = Math.ceil(totalCount / currentLimit);
  renderPagination(totalPages, currentPage);
});
