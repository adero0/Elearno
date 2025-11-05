<?php
// adaptive_engine.php
// contains function assign_recommendations_for_user($conn, $user_id)
function assign_recommendations_for_user($conn, $user_id) {
    // get latest profile
    $stmt = $conn->prepare("SELECT visual_score, auditory_score, kinesthetic_score, logical_score FROM learning_profiles WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($vis,$aud,$kin,$log);
    if (!$stmt->fetch()) return;
    // build priority list: pair style => score
    $styles = [
      'visual' => $vis,
      'auditory' => $aud,
      'kinesthetic' => $kin,
      'logical' => $log
    ];
    arsort($styles);
    $top = array_keys($styles);
    // map styles to tags or types
    $preferred = [];
    foreach ($top as $style) {
        if ($style === 'visual') $preferred[] = "type IN ('video','interactive') AND (tags LIKE '%visual%' OR tags LIKE '%diagram%')";
        if ($style === 'auditory') $preferred[] = "type IN ('interactive') AND (tags LIKE '%audio%' OR tags LIKE '%lecture%')";
        if ($style === 'kinesthetic') $preferred[] = "type = 'interactive' AND (tags LIKE '%practical%' OR tags LIKE '%exercise%')";
        if ($style === 'logical') $preferred[] = "type IN ('text','interactive') AND (tags LIKE '%logic%' OR tags LIKE '%problem%')";
    }
    // assign up to 5 recommendations: preferred first, fallback to any
    $assigned = [];
    foreach ($preferred as $cond) {
        $sql = "SELECT id FROM materials WHERE $cond ORDER BY difficulty ASC LIMIT 3";
        $res = $conn->query($sql);
        while ($r = $res->fetch_assoc()) {
            if (count($assigned) >= 5) break;
            $assigned[] = $r['id'];
        }
        if (count($assigned) >= 5) break;
    }
    if (count($assigned) < 5) {
        $res = $conn->query("SELECT id FROM materials ORDER BY created_at DESC LIMIT 5");
        while ($r = $res->fetch_assoc()) if (!in_array($r['id'],$assigned)) $assigned[] = $r['id'];
    }
    // insert assignments if not exists
    $stmt = $conn->prepare("INSERT INTO material_assignments (user_id, material_id) SELECT ?, ? FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM material_assignments WHERE user_id=? AND material_id=?) LIMIT 1");
    foreach ($assigned as $mid) {
        $stmt->bind_param("iiii", $user_id, $mid, $user_id, $mid);
        $stmt->execute();
    }
}
?>