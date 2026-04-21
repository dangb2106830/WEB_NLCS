<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Policy;
use Illuminate\Database\Seeder;

class MinhDangAiDemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $defaultImage = (string) (Blog::query()->value('image') ?: 'J0m20_td.jpg');
        $defaultAuthor = (string) (Blog::query()->value('author') ?: 'MinhDang Team');

        $blogs = [
            [
                'slug' => 'minhdang-tuyen-dung',
                'title' => 'MinhDang tuyển dụng nhân viên bán hàng và thu ngân',
                'intro' => 'MinhDang đang tuyển nhân viên bán hàng, thu ngân và hỗ trợ kho cho đợt mở rộng ngành điện gia dụng.',
                'content' => <<<TEXT
MinhDang đang tuyển dụng nhân viên bán hàng, thu ngân và hỗ trợ kho cho khu vực điện gia dụng.

Yêu cầu cơ bản:
- Giao tiếp tốt, thân thiện với khách hàng.
- Có thể xoay ca và làm việc cuối tuần.
- Ưu tiên ứng viên từng bán thiết bị điện gia dụng hoặc đồ dùng nhà bếp.

Quyền lợi:
- Được đào tạo về nhóm hàng Panasonic, Sharp, Philips, Electrolux và Sunhouse.
- Môi trường làm việc ổn định, có thưởng doanh số theo tháng.
- Có cơ hội chuyển lên vị trí tư vấn ngành hàng sau thời gian thử việc.

Ứng viên có thể nộp hồ sơ trực tiếp tại quầy dịch vụ khách hàng của MinhDang hoặc liên hệ fanpage để đặt lịch phỏng vấn.
TEXT,
                'created_date' => '2026-04-16 09:00:00',
                'image' => $defaultImage,
                'author' => $defaultAuthor,
            ],
            [
                'slug' => 'minhdang-uu-dai-dien-gia-dung-cuoi-tuan',
                'title' => 'MinhDang ưu đãi điện gia dụng cuối tuần',
                'intro' => 'Cuối tuần này MinhDang triển khai ưu đãi cho các nhóm hàng gia dụng, nhà bếp và thiết bị dọn dẹp.',
                'content' => <<<TEXT
MinhDang triển khai chương trình ưu đãi cuối tuần cho nhiều nhóm hàng điện gia dụng.

Các nhóm sản phẩm nổi bật trong đợt khuyến mãi:
- Nồi chiên không dầu, lò nướng, nồi cơm điện và máy xay sinh tố.
- Máy hút bụi, máy lọc không khí và các thiết bị dọn dẹp nhà cửa.
- Bình đun siêu tốc, chảo chống dính và bộ nồi dùng cho gia đình.

Khách hàng có thể tham khảo các thương hiệu đang có mặt tại MinhDang như Sunhouse, Panasonic, Sharp, Philips và Electrolux.
Một số sản phẩm có thể áp dụng giá ưu đãi theo số lượng tồn kho thực tế.

Khi cần tư vấn, khách hàng chỉ cần cung cấp nhu cầu như tiết kiệm điện, giá phù hợp, dễ vệ sinh hoặc gia đình có trẻ nhỏ để nhân viên MinhDang gợi ý nhanh hơn.
TEXT,
                'created_date' => '2026-04-16 09:30:00',
                'image' => $defaultImage,
                'author' => 'MinhDang Content Team',
            ],
            [
                'slug' => 'minhdang-doi-cu-lay-moi-dien-gia-dung',
                'title' => 'MinhDang triển khai chương trình đổi cũ lấy mới',
                'intro' => 'Khách hàng có thể mang thiết bị điện gia dụng cũ đến MinhDang để được hỗ trợ định giá và đổi sang sản phẩm mới.',
                'content' => <<<TEXT
MinhDang triển khai chương trình đổi cũ lấy mới dành cho một số nhóm hàng điện gia dụng.

Sản phẩm có thể tham gia chương trình:
- Nồi cơm điện.
- Máy xay sinh tố.
- Máy hút bụi.
- Bình đun siêu tốc.
- Lò nướng và nồi chiên không dầu.

Điều kiện cơ bản:
- Thiết bị cũ còn nhận diện được model hoặc thương hiệu.
- Khách hàng mang sản phẩm đến quầy dịch vụ để được kiểm tra tình trạng thực tế.
- Giá hỗ trợ thu cũ sẽ được xác nhận trực tiếp tùy tình trạng máy và nhóm hàng.

Chương trình đổi cũ lấy mới tại MinhDang không áp dụng cho tất cả sản phẩm và có thể thay đổi theo từng thời điểm khuyến mãi. Khách hàng nên hỏi trước khi đến cửa hàng để được xác nhận nhóm hàng đang áp dụng.
TEXT,
                'created_date' => '2026-04-16 10:00:00',
                'image' => $defaultImage,
                'author' => 'MinhDang Marketing',
            ],
            [
                'slug' => 'minhdang-huong-dan-chon-noi-chien-khong-dau',
                'title' => 'MinhDang hướng dẫn chọn nồi chiên không dầu',
                'intro' => 'Nếu bạn đang phân vân giữa nhu cầu gia đình nhỏ, dễ vệ sinh hay ưu tiên giá hợp lý, MinhDang gợi ý vài tiêu chí chọn nồi chiên không dầu.',
                'content' => <<<TEXT
Khi chọn nồi chiên không dầu tại MinhDang, khách hàng nên cân nhắc các yếu tố sau:

1. Dung tích sử dụng:
- Gia đình 1 đến 2 người có thể chọn dung tích vừa.
- Gia đình đông người nên ưu tiên dung tích lớn hơn để nấu được nhiều món trong một lần.

2. Mức giá:
- Nếu cần tối ưu ngân sách, khách hàng nên hỏi nhóm sản phẩm có giá tốt và đang còn tồn kho.
- Nếu ưu tiên trải nghiệm cao hơn, có thể so sánh thêm các thương hiệu lớn để cân bằng giữa tính năng và giá.

3. Mức độ vệ sinh:
- Nên ưu tiên loại có khay hoặc lòng nồi tháo rời, dễ vệ sinh sau khi sử dụng.

4. Nhu cầu sử dụng:
- Nếu thường xuyên nướng, quay hoặc làm món ít dầu mỡ, nồi chiên không dầu là lựa chọn phù hợp cho căn bếp hiện đại.

Nhân viên MinhDang có thể gợi ý nhanh theo nhu cầu gia đình, ngân sách và thương hiệu bạn đang quan tâm.
TEXT,
                'created_date' => '2026-04-16 10:30:00',
                'image' => $defaultImage,
                'author' => 'MinhDang Tư Vấn',
            ],
            [
                'slug' => 'minhdang-goi-y-thiet-bi-don-dep-gia-dinh',
                'title' => 'MinhDang gợi ý thiết bị dọn dẹp cho gia đình',
                'intro' => 'Máy hút bụi, máy lọc không khí và thiết bị dọn dẹp là nhóm hàng được nhiều khách hỏi tại MinhDang.',
                'content' => <<<TEXT
Tại MinhDang, nhóm thiết bị dọn dẹp nhà cửa được nhiều khách hàng quan tâm vì dễ kết hợp cho căn hộ và gia đình nhỏ.

Khi chọn thiết bị dọn dẹp, khách hàng thường hỏi:
- Loại nào gọn nhẹ, dễ cầm và dễ cất.
- Loại nào phù hợp cho nhà có thú cưng hoặc nhiều bụi mịn.
- Loại nào dễ vệ sinh hộp chứa bụi và thay phụ kiện.

MinhDang hiện có các nhóm sản phẩm như máy hút bụi, máy lọc không khí và một số thiết bị hỗ trợ vệ sinh gia đình đến từ nhiều thương hiệu khác nhau.

Nếu khách hàng chưa xác định rõ model, nhân viên có thể gợi ý theo diện tích phòng, tần suất sử dụng và mức giá mong muốn.
TEXT,
                'created_date' => '2026-04-16 11:00:00',
                'image' => $defaultImage,
                'author' => 'MinhDang Home Care',
            ],
            [
                'slug' => 'minhdang-huong-dan-mua-hang-online',
                'title' => 'MinhDang hướng dẫn mua hàng online và nhận hỗ trợ nhanh',
                'intro' => 'Khách hàng có thể nhắn chatbot hoặc liên hệ quầy tư vấn để được gợi ý sản phẩm, kiểm tra tồn kho và hỏi chính sách.',
                'content' => <<<TEXT
MinhDang hỗ trợ khách hàng mua hàng online theo quy trình đơn giản:

1. Chọn nhóm sản phẩm hoặc nêu rõ nhu cầu sử dụng.
2. Hỏi thêm về thương hiệu, mức giá, sản phẩm bán chạy hoặc sản phẩm đang có ưu đãi.
3. Kiểm tra chính sách giao hàng, đổi trả, bảo hành và thanh toán trước khi chốt đơn.

Chatbot MinhDang có thể hỗ trợ:
- Tìm sản phẩm phù hợp theo từ khóa.
- Trả lời các câu hỏi thống kê như số lượng thương hiệu, giá trung bình hoặc thương hiệu nổi bật theo dữ liệu hiện có.
- Trả lời các câu hỏi chính sách khi trong hệ thống đã có dữ liệu blog và policy phù hợp.

Nếu cần xác nhận tình trạng hàng thực tế, khách hàng nên liên hệ trực tiếp MinhDang trước khi đến cửa hàng.
TEXT,
                'created_date' => '2026-04-16 11:30:00',
                'image' => $defaultImage,
                'author' => 'MinhDang Digital Team',
            ],
        ];

        foreach ($blogs as $blogData) {
            Blog::query()->updateOrCreate(
                ['slug' => $blogData['slug']],
                [
                    ...$blogData,
                    'embedding' => null,
                ]
            );
        }

        $policies = [
            [
                'title' => 'Chính sách đổi trả và hoàn tiền tại MinhDang',
                'content' => <<<TEXT
MinhDang hỗ trợ đổi trả trong vòng 7 ngày kể từ khi khách nhận hàng đối với sản phẩm bị lỗi kỹ thuật, giao sai mẫu hoặc thiếu phụ kiện so với mô tả.

Điều kiện áp dụng:
- Sản phẩm còn đầy đủ phụ kiện, phiếu bảo hành và hộp nếu có.
- Sản phẩm không bị rơi vỡ, vào nước, cháy nổ hoặc hư hỏng do tác động từ người dùng.
- Khách hàng cung cấp thông tin đơn hàng hoặc số điện thoại mua hàng để MinhDang kiểm tra.

Trường hợp không áp dụng đổi trả:
- Sản phẩm đã qua sử dụng sai hướng dẫn.
- Sản phẩm bị trầy xước nặng, bể vỡ hoặc thiếu phụ kiện do người dùng làm mất.
- Nhóm hàng thanh lý hoặc nhóm hàng được thông báo không áp dụng đổi trả riêng.

Nếu đủ điều kiện, MinhDang sẽ ưu tiên đổi sản phẩm tương đương. Trường hợp không còn hàng thay thế, cửa hàng sẽ hỗ trợ hoàn tiền theo quy trình nội bộ.
TEXT,
            ],
            [
                'title' => 'Chính sách giao hàng và kiểm hàng tại MinhDang',
                'content' => <<<TEXT
MinhDang hỗ trợ giao hàng nội thành và giao liên tỉnh tùy khu vực phục vụ.

Khách hàng được kiểm tra ngoại quan sản phẩm khi nhận hàng, bao gồm:
- Tình trạng thùng hàng.
- Tem niêm phong hoặc phụ kiện cơ bản đi kèm.
- Mã sản phẩm nếu có thể đối chiếu nhanh trên đơn hàng.

Lưu ý:
- Việc kiểm tra khi giao hàng không đồng nghĩa với sử dụng thử toàn bộ chức năng.
- Nếu phát hiện móp méo, giao sai mẫu hoặc thiếu phụ kiện, khách hàng nên báo ngay cho MinhDang tại thời điểm nhận hàng.
- Thời gian giao hàng có thể thay đổi theo khu vực, thời tiết và tình trạng xử lý đơn.
TEXT,
            ],
            [
                'title' => 'Chính sách bảo hành sản phẩm điện gia dụng tại MinhDang',
                'content' => <<<TEXT
MinhDang hỗ trợ tiếp nhận bảo hành theo chính sách của hãng và theo tình trạng thực tế của sản phẩm.

Thông tin khách hàng cần chuẩn bị:
- Số điện thoại mua hàng hoặc mã đơn hàng.
- Phiếu bảo hành nếu sản phẩm có cấp riêng.
- Mô tả lỗi gặp phải trong quá trình sử dụng.

Một số lưu ý bảo hành:
- Bảo hành không áp dụng cho lỗi do rơi vỡ, ngập nước, sử dụng sai điện áp hoặc tự ý tháo lắp.
- Thời gian xử lý bảo hành tùy theo hãng và loại sản phẩm.
- MinhDang có thể hỗ trợ hướng dẫn khách mang máy đến quầy dịch vụ hoặc gửi về trung tâm bảo hành phù hợp.
TEXT,
            ],
            [
                'title' => 'Chính sách thanh toán và xuất hóa đơn tại MinhDang',
                'content' => <<<TEXT
MinhDang hỗ trợ các hình thức thanh toán như tiền mặt, chuyển khoản và các phương thức thanh toán điện tử đang được cửa hàng áp dụng.

Khi cần xuất hóa đơn, khách hàng nên cung cấp thông tin sớm trong lúc đặt hàng để cửa hàng xử lý thuận tiện hơn.

Đối với đơn hàng online:
- Khách hàng nên xác nhận tổng tiền, phí giao hàng và người nhận trước khi chốt đơn.
- Nếu cần điều chỉnh thông tin hóa đơn, khách hàng nên liên hệ MinhDang ngay trong ngày mua hàng.
TEXT,
            ],
            [
                'title' => 'Chính sách khuyến mãi và tích điểm thành viên tại MinhDang',
                'content' => <<<TEXT
MinhDang có thể triển khai các chương trình khuyến mãi theo thời điểm như giảm giá cuối tuần, ưu đãi theo nhóm hàng hoặc quà tặng kèm cho một số sản phẩm.

Lưu ý về khuyến mãi:
- Không phải mọi sản phẩm đều áp dụng cùng một mức ưu đãi.
- Quà tặng và ưu đãi có thể thay đổi theo tồn kho thực tế.
- Một số chương trình có thể không áp dụng đồng thời với các ưu đãi khác.

Nếu có chương trình tích điểm thành viên, MinhDang sẽ thông báo điều kiện áp dụng tại quầy hoặc trên kênh bán hàng chính thức.
TEXT,
            ],
            [
                'title' => 'Chính sách đổi cũ lấy mới tại MinhDang',
                'content' => <<<TEXT
MinhDang hỗ trợ chương trình đổi cũ lấy mới cho một số nhóm hàng điện gia dụng theo từng thời điểm.

Nhóm hàng thường được xem xét hỗ trợ:
- Nồi cơm điện.
- Máy xay sinh tố.
- Máy hút bụi.
- Bình đun siêu tốc.
- Nồi chiên không dầu.

Điều kiện tham khảo:
- Sản phẩm cũ còn nhận diện được model hoặc thương hiệu.
- Khách hàng mang sản phẩm trực tiếp đến cửa hàng để kiểm tra.
- Mức hỗ trợ thu cũ phụ thuộc tình trạng thực tế và chương trình đang áp dụng.

Khách hàng nên liên hệ MinhDang trước để xác nhận nhóm hàng nào đang được áp dụng đổi cũ lấy mới tại thời điểm hiện tại.
TEXT,
            ],
        ];

        foreach ($policies as $policyData) {
            Policy::query()->updateOrCreate(
                ['title' => $policyData['title']],
                [
                    'content' => $policyData['content'],
                    'embedding' => null,
                ]
            );
        }
    }
}
