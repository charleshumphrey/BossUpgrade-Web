document.querySelectorAll("#confirm-archive").forEach((button) => {
    button.addEventListener("click", function () {
        const menuId = this.dataset.menuId;

        fetch(`/menu-items/archive/${menuId}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ menuId }),
        })
            .then((response) => {
                console.log("Response status:", response.status);
                return response.json().then((data) => {
                    return { data, status: response.status };
                });
            })
            .then(({ data, status }) => {
                if (status === 200 && data.success) {
                    console.log("Menu item archived successfully");
                } else {
                    console.error(
                        "Failed to archive menu item:",
                        data.message || "Unknown error"
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    });
});

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".--jb-modal").forEach((button) => {
        button.addEventListener("click", () => {
            const modalId = button.dataset.target;
            document.getElementById(modalId).classList.remove("hidden");
        });
    });

    document.querySelectorAll(".--jb-modal-close").forEach((button) => {
        button.addEventListener("click", () => {
            button.closest(".modal").classList.add("hidden");
        });
    });
});
